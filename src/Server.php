<?php

namespace OceanProject;

use OceanProject\Models;
use OceanProject\Logger;
use OceanProject\Bot\XatBot;
use OceanProject\Extensions;
use OceanProject\API\BaseAPI;
use OceanProject\Bot\XatVariables;

use Illuminate\Database\Capsule\Manager as Capsule;

class Server
{
    public $name;
    public $capsule;
    public $params;
    public $bot;
    public $xatBots;
    public $started;
    public $lastAutorestart;
    public $botsToBeStarted;

    public function __construct($name)
    {
        $this->name = $name;
        $this->started = time();
        $this->init();
    }

    private function init()
    {
        Logger::init($this->name);
        $this->initDatabase();
        $this->initVariables();
        $this->initAPI();
        $this->initBots();
        $this->initExtensions();
        $this->initIPC();
        Logger::getLogger()->info('Server is ready!');
    }

    private function initDatabase()
    {
        Logger::getLogger()->info('Loading database...');
        $infos = json_decode(file_get_contents('config.json'), true)['database'];
        $this->capsule = new Capsule();

        $this->capsule->addConnection(
            [
            'driver'    => $infos['driver'],
            'host'      => $infos['host'],
            'database'  => $infos['database'],
            'username'  => $infos['username'],
            'password'  => $infos['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
            ]
        );

        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    private function initVariables()
    {
        Logger::getLogger()->info('Loading variables...');
        XatVariables::init();
        XatVariables::update();
    }

    private function initAPI()
    {
        Logger::getLogger()->info('Loading API...');
        $this->params     = BaseAPI::init();
        $this->currentBot = &$this->params['botID'];
        $this->bot        = &$this->params['bot'];
    }

    private function initBots()
    {
        Logger::getLogger()->info('Loading bots...');
        $this->xatBots = [];
        $this->lastAutorestart = 1;
        $this->botsToBeStarted = [];
    }

    private function initExtensions()
    {
        Logger::getLogger()->info('Loading extensions...');
        Extensions::readExtensions();
    }

    private function initIPC()
    {
        Logger::getLogger()->info('Loading IPC...');
        $this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!$this->socket) {
            exit('Cannot create unix socket');
        }
        
        if (file_exists('sockets' . DIRECTORY_SEPARATOR . strtolower($this->name) . '.sock')) {
            if (@socket_connect($this->socket, 'sockets' . DIRECTORY_SEPARATOR . strtolower($this->name) . '.sock')) {
                exit($argv[1] . ' server is already started');
            } else {
                unlink('sockets' . DIRECTORY_SEPARATOR . strtolower($this->name) . '.sock');
            }
        }
        $ret = socket_bind($this->socket, 'sockets' . DIRECTORY_SEPARATOR . strtolower($this->name) . '.sock');
        if (!$ret) {
            exit();
        }
        
        $ret = socket_set_nonblock($this->socket);
        if (!$ret) {
            exit();
        }
            
        $ret = socket_listen($this->socket);
        if (!$ret) {
            exit();
        }

        chmod('sockets' . DIRECTORY_SEPARATOR . strtolower($this->name) . '.sock', 0777);
    }

    public function handle()
    {
        while (1) {
            $tmpClient = @socket_accept($this->socket);
            if (@socket_set_nonblock($tmpClient)) {
                $clients[] = $tmpClient;
            }

            if (!empty($clients)) {
                // Socket's loop
                foreach ($clients as $client) {
                    $packet = null;
                    do {
                        $lastSize = strlen($packet);
                        $packet .= socket_read($client, 1024);
                    } while ($lastSize != strlen($packet));

                    if (strlen($packet) == 0) {
                        continue;
                    }

                    Logger::getLogger()->notice('From socket: ' . $packet);
                    $args    = explode(' ', trim($packet));
                    $command = $args[0];
                    $botid   = $args[1] ?? null;
                    $return  = null;

                    switch ($command) {
                        case 'start':
                            if ($this->start($botid) === true) {
                                $return = 'Success';
                            } else {
                                $return = 'Error';
                            }
                            break;

                        case 'restart':
                            if ($this->restart($botid) === true) {
                                $return = 'Success';
                            } else {
                                $return = 'Error';
                            }
                            break;

                        case 'stop':
                            if ($this->stop($botid) === true) {
                                $return = 'Success';
                            } else {
                                $return = 'Error';
                            }
                            break;

                        case 'refresh':
                            unset($args[0]);
                            unset($args[1]);
                            if ($this->refresh($botid, implode(' ', $args)) === true) {
                                $return = 'Success';
                            } else {
                                $return = 'Error';
                            }
                            break;

                        case 'server_status':
                            $return = json_encode([
                                'bots'        => sizeof($this->xatBots),
                                'memory'      => round(memory_get_usage(true) / 1024 / 1024),
                                'cpu'         => trim(exec('ps -p ' . getmypid() . ' -o %cpu')),
                                'timestarted' => $this->started
                            ]);
                            break;

                        case 'users_count':
                            if (isset($this->xatBots[$botid])) {
                                $return = sizeof($this->xatBots[$botid]->users);
                            } else {
                                $return = 0;
                            }
                            break;

                        case 'reload':
                            $this->readExtensions();
                            $return = 'Extension reloaded!';
                            break;

                        default:
                            $return = 'Unknow command!';
                            break;
                    }

                    socket_write($client, $return);
                    unset($clients);
                }
            } else {
                // Bots loop
                if ($this->lastAutorestart < time()) {
                    $results = Capsule::table('bots')
                        ->join('servers', 'bots.server_id', '=', 'servers.id')
                        ->where('bots.autorestart', true)
                        ->where('bots.bot_status_id', '1')
                        ->orderBy('bots.id', 'ASC')
                        ->select('bots.id')
                        ->get();

                    for ($i = 0; $i < sizeof($results); $i++) {
                        $this->botsToBeStarted[] = $results[$i]->id;
                    }

                    $this->lastAutorestart = time() + 600;
                }

                if (!empty($this->botsToBeStarted)) {
                    $botid = min($this->botsToBeStarted);
                    if (!array_key_exists($botid, $this->xatBots)) {
                        $this->start($botid);
                    }

                    unset($this->botsToBeStarted[array_search($botid, $this->botsToBeStarted)]);
                    unset($botid);
                }

                foreach ($this->xatBots as $botid => $Ocean) {
                    $this->currentBot = $botid;
                    $this->bot        = $Ocean;
                    $Ocean->network->tick();

                    usleep(5000);
                    try {
                        while (1) {
                            if (!$Ocean->network->socket->isConnected()) {
                                Logger::getLogger()->critical('[' . $botid . '] Socket not connected!');
                                $Ocean->refresh();
                                break;
                            }

                            if ($Ocean->stopped) {
                                stop($botid);
                                break;
                            }

                            $packet = $Ocean->network->socket->read();

                            if ($packet === false) {
                                Logger::getLogger()->critical('[' . $botid . '] Packet false!');
                                $Ocean->network->reconnect();
                                break;
                            }

                            if (empty($packet)) {
                                break;
                            }

                            if (!isset($packet['node'])) {
                                var_dump($packet);
                                break;
                            }

                            $hook   = null;
                            $args   = [];
                            $unknow = false;
                            
                            $parseElements = ['u', 'd'];
                            foreach ($parseElements as $parse) {
                                if (isset($packet['elements'][$parse])) {
                                    $packet['elements'][$parse] = $Ocean->network->parseID($packet['elements'][$parse]);
                                }
                            }

                            switch ($packet['node']) {
                                case 'abort':
                                    $hook   = 'onAbort'; // onAbort($array)
                                    $args[] = $packet['elements'];
                                    break;
                                    
                                case 'a':
                                    $hook   = 'onTransfer'; // onTransfer($from, $type, $message, $to, $xats, $days)
                                    $args[] = $packet['elements']['u'];
                                    $args[] = $packet['elements']['k'];
                                    $args[] = $packet['elements']['t'] ?? '';
                                    $args[] = $packet['elements']['b'];
                                    $args[] = $packet['elements']['x'] ?? 0;
                                    $args[] = $packet['elements']['s'] ?? 0;
                                    break;

                                case 'bl':
                                    $hook   = 'onBlast'; // onBlast($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'c':
                                    $hook   = 'onControlMessage'; // onControlMessage($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'done':
                                    $hook   = 'onDone'; // onDone($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'dup':
                                    $hook   = 'onDup'; // onDup()
                                    break;

                                case 'f':
                                    $hook   = 'onFriendList'; // onFriendList($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'g':
                                    $hook   = 'onOpenApp'; // onOpenApp($who, $app)
                                    $args[] = $packet['elements']['u'];
                                    $args[] = $packet['elements']['x'];
                                    break;

                                case 'gp':
                                    $hook   = 'onGroupPowers'; // onGroupPowers($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'i':
                                    $hook   = 'onChatInfo'; // onChatInfo($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'idle':
                                    $hook   = 'onIdle'; // onIdle($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'k':
                                    $hook   = 'onKick'; // onKick($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'l':
                                    $hook   = 'onUserLeave'; // onUserLeave($who)
                                    $args[] = $packet['elements']['u'];
                                    break;
                                    
                                case 'ldone':
                                    // meh
                                    break;
                                    
                                case 'logout':
                                    $hook   = 'onLogout'; // onLogout($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'm':
                                    if (isset($packet['elements']['u'])) {
                                        if (!$Ocean->done) {
                                            if (isset($packet['elements']['i'])) {
                                                $Ocean->messageCount = $packet['elements']['i'];
                                            } else {
                                                $Ocean->messageCount = 0;
                                            }
                                        } else {
                                            $Ocean->messageCount++;
                                        }

                                        if ($packet['elements']['t'] == '/RTypeOn' ||
                                            $packet['elements']['t'] == '/RTypeOff') {
                                            continue;
                                        }

                                        if (!isset($packet['elements']['s'])) {
                                            if (!isset($packet['elements']['p']) && isset($packet['elements']['i'])) {
                                                $hook   = 'onMessage'; // onMessage($who, $message)
                                                $args[] = $packet['elements']['u'];
                                                $args[] = $packet['elements']['t'];
                                            } elseif (isset($packet['elements']['p'])) {
                                                $hook   = 'onRankMessage'; //onRankMessage($who,$message,$reason,$array)
                                                $args[] = $packet['elements']['u'];
                                                $args[] = $packet['elements']['t'];
                                                $args[] = $packet['elements']['p'];
                                                $args[] = $packet['elements'];
                                            }
                                        } elseif ($packet['elements']['s'] & 1) {
                                            $hook   = 'onOldMessage'; // onOldMessage($who, $message)
                                            $args[] = $packet['elements']['d'] ?? $packet['elements']['u'];
                                            $args[] = $packet['elements']['t'];
                                        }
                                    }
                                    break;

                                case 'o':
                                    // Old User
                                    break;

                                case 'p':
                                    if ($packet['elements']['t'] == '/RTypeOn' ||
                                        $packet['elements']['t'] == '/RTypeOff') {
                                        continue;
                                    }

                                    $hook   = (isset($packet['elements']['s'])) ? 'onPC' : 'onPM'; //onP*($who,$message)
                                    $args[] = $packet['elements']['u'];
                                    $args[] = $packet['elements']['t'];
                                    break;

                                case 'q':
                                    $hook   = 'onRedirect'; // onRedirect($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'u':
                                    $hook   = 'onUserJoined'; // onUserJoined($array)
                                    $args[] = $packet['elements']['u'];
                                    $args[] = $packet['elements'];
                                    break;
                                    
                                case 'v':
                                    $hook   = 'onLoginInfo'; // onLoginInfo($array)
                                    $args[] = $packet['elements'];
                                    break;

                                case 'w':
                                    $hook   = 'onPools'; // onPools($array)
                                    $args[] = $packet['elements'];
                                    break;
                                    
                                case 'x':
                                    $hook   = 'onApp'; // onApp($who, $app, $elements)
                                    $args[] = $packet['elements']['u'];
                                    $args[] = $packet['elements']['i'];
                                    $args[] = $packet['elements'];
                                    break;

                                case 'z':
                                    $hook   = 'onTickle'; // onTickle($who, $array)
                                    $args[] = $packet['elements']['u'];
                                    $args[] = $packet['elements'];
                                    break;

                                default:
                                    $unknow = true;
                                    break;
                            }

                            if (in_array($hook, ['onMessage', 'onPM', 'onPC']) &&
                                $args[1][0] == $Ocean->data->customcommand) {
                                $args[1] = explode(' ', trim($args[1]));
                                $command = substr($args[1][0], 1);

                                if (isset($Ocean->aliases[$command])) {
                                    $args[1][0] = $Ocean->data->customcommand . $Ocean->aliases[$command];
                                    $args[1] = explode(' ', trim(implode(' ', $args[1])));
                                    $command = substr($args[1][0], 1);
                                }
                                
                                if ($hook == 'onMessage') {
                                    $args[2] = 1;
                                } elseif ($hook == 'onPM') {
                                    $args[2] = 2;
                                } elseif ($hook == 'onPC') {
                                    $args[2] = 3;
                                }

                                $this->dispatch('Modules', 'onCommand', $args);
                                $isDispatched = $this->dispatch('Commands', $command, $args);

                                if (!$isDispatched) {
                                    $this->dispatch('Commands', 'handlecustomcommands', $args);
                                }
                            } else {
                                if (!$unknow && !empty($hook)) {
                                    $this->dispatch('Modules', $hook, $args);
                                } elseif ($unknow) {
                                    Logger::getLogger()->critical(
                                        '[' . $botid . '] Unknow node ['.$packet['node'].'] on chat FIXME'
                                    );
                                }
                            }
                        }
                    } catch (Exception $e) {
                        Logger::getLogger()->critical('[' . $botid . '] Message: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    private function dispatch($type, $name, $args)
    {
        $extensionsList = Extensions::getExtensionsList();

        if (!isset($extensionsList[$type][$name])) {
            return false;
        }

        foreach ($extensionsList[$type][$name] as $extensionName => $function) {
            try {
                call_user_func_array($function, $args);
            } catch (TypeError $e) {
                Logger::getLogger()->critical('Error dispatch, message: ' . $e->getMessage());
            }
        }

        return true;
    }

    public function start($botid)
    {
        try {
            $bot = Models\Bot::find($botid);
            $Ocean = new XatBot($bot);
            $bot->bot_status_id = Models\BotStatus::where('name', 'Online')->first()->id;
            $bot->save();
            $this->xatBots[$botid] = $Ocean;
        } catch (Exception $e) {
            Logger::getLogger()->critical('Error start, message: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    public function restart($botid)
    {
        $this->stop($botid);
        $this->start($botid);
    }

    public function stop($botid)
    {
        if (isset($this->xatBots[$botid])) {
            $bot = Models\Bot::find($botid);
            $bot->bot_status_id = Models\BotStatus::where('name', 'Offline')->first()->id;
            $bot->save();

            unset($this->xatBots[$botid]);
            return true;
        }

        return false;
    }

    public function refresh($botid, $message)
    {
        if (isset($this->xatBots[$botid])) {
            $this->xatBots[$botid]->refresh();
            $this->xatBots[$botid]->network->sendMessage($message);
            return true;
        }

        return false;
    }
}
