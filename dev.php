<?php

require_once 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Ocean\Xat\Variables;
use Ocean\Xat\API;
use Ocean\Xat\Bot;
use Ocean\Xat\Commands;
use Ocean\Xat\Modules;
use Pimple\Container;

$log = new Logger('bot');
$log->pushHandler(new StreamHandler('logs/logs.log', Logger::ERROR));
$log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

$container = new Container();
$container->register(new Commands());
$container->register(new Modules());

$log->info('Loading variables...');
Variables::init();
Variables::update();

$log->info('Loading API...');
$params     = API\BaseAPI::init();
$currentBot = &$params['botID'];
$bot        = &$params['bot'];

$log->info('Loading bots...');
$xatBots = [];
foreach (Variables::getBots() as $botid => $bot) {
    $xatBots[$botid] = new Bot($bot);
}

$log->info('Server is ready!');
while (1) {
    foreach ($xatBots as $botid => $Ocean) {
        $currentBot = $botid;
        $bot        = $Ocean;
        usleep(5000);
        try {
            while (1) {
                if (!$Ocean->network->socket->isConnected()) {
                    $log->critical('Socket not connected!');
                    exit('You have an error in your code or socket died.');
                    break;
                }

                $packet = $Ocean->network->socket->read();

                if ($packet === false) {
                    $log->critical('ERROR packet false!');
                    exit('You have an error in your code or socket died.');
                    break;
                }

                if (empty($packet)) {
                    break;
                }

                if (!isset($packet['node'])) {
                    $log->warning('Missing Node', [
                        'packet' => $packet
                    ]);
                    break;
                }

                $hook   = null;
                $args   = [];
                $unknow = false;

                switch ($packet['node']) {
                    case 'm':
                        if (!isset($packet['elements']['s'])) {
                            if (!isset($packet['elements']['p']) && isset($packet['elements']['i'])) {
                                $hook   = 'onMessage'; // onMessage($who, $message)
                                $args[] = $Ocean->network->parseID($packet['elements']['u']);
                                $args[] = $packet['elements']['t'];
                            } else {
                                $hook   = 'onOldMessage'; // onOldMessage($who, $message)
                                $uid    = $packet['elements']['d'] ?? $packet['elements']['u'];
                                $args[] = $Ocean->network->parseID($uid);
                                $args[] = $packet['elements']['t'];
                            }
                        }
                        break;

                    case 'p':
                        if ($packet['elements']['t'] == '/RTypeOn' || $packet['elements']['t'] == '/RTypeOff') {
                            continue;
                        }

                        $hook   = (isset($packet['elements']['s'])) ? 'onPC' : 'onPM';  // onP*($who, $message)
                        $args[] = $Ocean->network->parseID($packet['elements']['u']);
                        $args[] = $packet['elements']['t'];
                        break;

                    case 'u':
                        $hook   = 'onUserJoined'; // onUserJoined($array)
                        $args[] = $Ocean->network->parseID($packet['elements']['u']);
                        $args[] = $packet['elements'];
                        break;

                    case 'z':
                        $hook   = 'onTickle'; // onTickle($who, $array)
                        $args[] = $Ocean->network->parseID($packet['elements']['u']);
                        $args[] = $packet['elements'];
                        break;

                    case 'l':
                        $hook   = 'onUserLeave'; // onUserLeave($who)
                        $args[] = $Ocean->network->parseID($packet['elements']['u']);
                        break;

                    case 'a':
                        $hook   = 'onTransfer'; // onTransfer($from, $to, $xats, $days, $message)
                        $args[] = $Ocean->network->parseID($packet['elements']['u']);
                        $args[] = $Ocean->network->parseID($packet['elements']['d']);
                        $args[] = $packet['elements']['x'];
                        $args[] = $packet['elements']['s'];
                        $args[] = $packet['elements']['t'];
                        break;

                    case 'f':
                        $hook   = 'onFriendList'; // onFriendList($array)
                        $args[] = $packet['elements'];
                        break;

                    case 'x':
                        $hook   = 'onApp'; // onApp($who, $app, $elements)
                        $args[] = $Ocean->network->parseID($packet['elements']['u']);
                        $args[] = $packet['elements']['i'];
                        $args[] = $packet['elements'];
                        break;

                    case 'done':
                        $hook   = 'onDone'; // onDone($array)
                        $args[] = $packet['elements'];
                        break;

                    case 'idle':
                        $hook   = 'onIdle'; // onIdle($array)
                        $args[] = $packet['elements'];
                        break;

                    case 'dup':
                        $hook   = 'onDup'; // onDup()
                        break;

                    case 'i':
                        $hook   = 'onChatInfo'; // onChatInfo($array)
                        $args[] = $packet['elements'];
                        break;

                    case 'gp':
                        $hook   = 'onGroupPowers'; // onGroupPowers($array)
                        $args[] = $packet['elements'];
                        break;

                    case 'c':
                        $hook   = 'onControlMessage'; // onControlMessage($array)
                        $args[] = $packet['elements'];
                        break;

                    case 'o':
                        // Old User
                        break;

                    default:
                        $unknow = true;
                        break;
                }

                if (in_array($hook, ['onMessage', 'onPM', 'onPC']) && $args[1][0] == '!') {
                    $args[1] = explode(' ', trim($args[1]));
                    $command = substr($args[1][0], 1);

                    if ($hook == 'onMessage') {
                        $args[2] = 1;
                    } elseif ($hook == 'onPM') {
                        $args[2] = 2;
                    } elseif ($hook == 'onPC') {
                        $args[2] = 3;
                    }
                    call_user_func_array($container['commands'][$command], $args);
                } else {
                    if (!$unknow && !empty($hook)) {
                        call_user_func_array($container['modules'][$hook], $args);
                    } elseif ($unknow) {
                        $log->error('Unknow node ['.$packet['node'].'] on chat FIXME');
                    }
                }
            }
        } catch (Exception $e) {
            $log->critical('Error botid: ', [
                    'botid' => $botid,
                    'error' => $e->getMessage()
            ]);
        }
    }
}
