<?php

namespace xatbot\Bot;

use Illuminate\Database\Capsule\Manager as Capsule;
use xatbot\Models\Bot;
use xatbot\API\DataAPI;
use xatbot\Logger;

class XatBot
{
    public $network;
    public $data;
    public $chatInfo;
    public $users;
    public $started;
    public $minranks;
    public $aliases;
    public $responses;
    public $stafflist;
    public $badwords;
    public $botlangs;
    public $autotemps;
    public $autobans;
    public $linksfilter;
    public $customcommands;
    public $snitchlist;
    public $messageCount;
    public $isPremium;
    public $stopped;
    public $done;
    public $packetsinqueue;
    public $refreshing;

    public function __construct(Bot $data, $refreshing = false)
    {
        $this->data           = $data;
        $this->started        = time();
        $this->stopped        = false;
        $this->aliases        = $this->setAliases();
        $this->minranks       = $this->setMinranks();
        $this->botlangs       = $this->setBotlangs();
        $this->badwords       = $this->setBadwords();
        $this->autobans       = $this->setAutobanList();
        $this->responses      = $this->setResponses();
        $this->stafflist      = $this->setStafflist();
        $this->autotemps      = $this->setAutotempList();
        $this->linksfilter    = $this->setLinksfilter();
        $this->customcommands = $this->setCustomCommands();
        $this->snitchlist     = $this->setSnitchList();
        $this->packetsinqueue = [];
        $this->refreshing     = $refreshing;

        if ($this->data->premium > time() && $this->data->premiumfreeze == 1) {
            $this->isPremium = true;
        } else {
            $this->isPremium = false;
        }

        $this->network = new XatNetwork($this->data);
    }

    public function setMinranks()
    {
        $results = Capsule::table('commands')
                    ->leftJoin('bot_command_minrank', function ($leftjoin) {
                        $leftjoin->on('bot_command_minrank.command_id', '=', 'commands.id')
                            ->on('bot_command_minrank.bot_id', '=', Capsule::raw($this->data->id));
                    })
                    ->leftJoin('minranks', 'bot_command_minrank.minrank_id', '=', 'minranks.id')
                    ->select('commands.name', 'minranks.level', 'commands.default_level')
                    ->get()
                    ->toArray();

        for ($i = 0; $i < sizeof($results); $i++) {
            if (empty($results[$i]->level)) {
                $results[$i]->level = $results[$i]->default_level;
            }
        }

        return array_column($results, 'level', 'name');
    }

    public function setBotlangs()
    {
        $results = Capsule::table('botlang')
                    ->rightJoin('botlang_sentences', 'botlang.botlang_sentences_id', '=', 'botlang_sentences.id')
                    ->where('botlang.bot_id', $this->data->id)
                    ->orWhereNull('botlang.bot_id')
                    ->select('botlang_sentences.name', 'botlang.value', 'botlang_sentences.sentences')
                    ->get()
                    ->toArray();

        $currentLanguage = $this->data->language->code;

        for ($i = 0; $i < sizeof($results); $i++) {
            if (empty($results[$i]->value)) {
                $sentences = json_decode($results[$i]->sentences);
                if (!isset($sentences->$currentLanguage) || empty($sentences->$currentLanguage)) {
                    $currentLanguage = 'en';
                }
                $results[$i]->value = $sentences->$currentLanguage;
            }
        }

        return array_column($results, 'value', 'name');
    }

    public function setAliases()
    {
        $results = Capsule::table('aliases')
                ->where('bot_id', $this->data->id)
                ->select('aliases.command', 'aliases.alias')
                ->get()
                ->toArray();

        return array_column($results, 'command', 'alias');
    }

    public function setResponses()
    {
        $results = Capsule::table('responses')
                ->where('bot_id', $this->data->id)
                ->select('phrase', 'response')
                ->get()
                ->toArray();

        return array_column($results, 'response', 'phrase');
    }

    public function setStafflist()
    {
        $results = Capsule::table('staffs')
                ->join('minranks', 'staffs.minrank_id', '=', 'minranks.id')
                ->where('bot_id', $this->data->id)
                ->select('staffs.xatid', 'minranks.level')
                ->get()
                ->toArray();

        return array_column($results, 'level', 'xatid');
    }

    public function setBadwords()
    {
        $results = Capsule::table('badwords')
                ->where('bot_id', $this->data->id)
                ->select('badword', 'method', 'hours')
                ->get()
                ->toArray();

        $badwords = [];
        for ($i = 0; $i < sizeof($results); $i++) {
            $badwords[$i]['badword'] = $results[$i]->badword;
            $badwords[$i]['method']  = $results[$i]->method;
            $badwords[$i]['hours']   = $results[$i]->hours;
        }

        return $badwords;
    }

    public function setLinksfilter()
    {
        $results = Capsule::table('linksfilter')
                ->where('bot_id', $this->data->id)
                ->select('link')
                ->get()
                ->toArray();

        return array_column($results, 'link');
    }

    public function setAutotempList()
    {
        $results = Capsule::table('autotemps')
                ->where('bot_id', $this->data->id)
                ->select('xatid', 'regname', 'hours')
                ->get();

        $list = [];
        for ($i = 0; $i < sizeof($results); $i++) {
            $list[$i]['xatid']   = $results[$i]->xatid;
            $list[$i]['regname'] = $results[$i]->regname;
            $list[$i]['hours']   = $results[$i]->hours;
        }

        return $list;
    }

    public function setAutobanList()
    {
        $results = Capsule::table('autobans')
                ->where('bot_id', $this->data->id)
                ->select('xatid', 'regname', 'hours', 'method')
                ->get();

        $list = [];
        for ($i = 0; $i < sizeof($results); $i++) {
            $list[$i]['xatid']   = $results[$i]->xatid;
            $list[$i]['regname'] = $results[$i]->regname;
            $list[$i]['hours']   = $results[$i]->hours;
            $list[$i]['method']  = $results[$i]->method;
        }

        return $list;
    }

    public function setCustomCommands()
    {
        $results = Capsule::table('customcommands')
                ->join('minranks', 'customcommands.minrank_id', '=', 'minranks.id')
                ->where('bot_id', $this->data->id)
                ->select('customcommands.command', 'customcommands.response', 'minranks.level')
                ->get();

        $list = [];
        for ($i = 0; $i < sizeof($results); $i++) {
            $list[$i]['command']  = $results[$i]->command;
            $list[$i]['response'] = $results[$i]->response;
            $list[$i]['level']    = $results[$i]->level;
        }

        return $list;
    }
    
    public function setSnitchList()
    {
        $results = Capsule::table('snitchs')
                ->where('bot_id', $this->data->id)
                ->select('xatid', 'regname')
                ->get();

        $list = [];
        for ($i = 0; $i < sizeof($results); $i++) {
            $list[$i]['xatid']   = $results[$i]->xatid;
            $list[$i]['regname'] = $results[$i]->regname;
        }

        return $list;
    }

    public function sendPacketsInQueue()
    {
        if (sizeof($this->packetsinqueue) > 0) {
            foreach ($this->packetsinqueue as $key => $value) {
                if (($key < round(microtime(true) * 1000))) {
                    $this->network->sendMessageAutoDetection($value['who'], $value['message'], $value['type']);
                    unset($this->packetsinqueue[$key]);
                }
            }
        } else {
            return false;
        }
    }

    public function botHasPower($id)
    {
        if (!$this->isPremium) {
            return false;
        }

        if (!is_numeric($id)) {
            $powers = XatVariables::getPowers();
            foreach ($powers as $key => $value) {
                if ($value['name'] == $id) {
                    $id = $key;
                    break;
                }
            }
            if (!is_numeric($id)) {
                return false;
            }
        }
        $id    = (int)$id;
        $index = (int)($id / 32) + 4;
        $bit   = (int)($id % 32);

        return (isset($this->network->logininfo['d' . $index]) &&
            ($this->network->logininfo['d' . $index] & (1 << $bit)));
    }

    public function flagToRank($id)
    {
        if (!isset($this->users[$id])) {
            return 0;
        }

        if ($this->users[$id]->isMain()) {
            return 5;
        } elseif ($this->users[$id]->isOwner()) {
            return 4;
        } elseif ($this->users[$id]->isMod()) {
            return 3;
        } elseif ($this->users[$id]->isMember()) {
            return 2;
        } elseif ($this->users[$id]->isGuest()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function stringToRank($string)
    {
        $string = strtolower($string);
        switch ($string) {
            case 'main':
                $rank = 5;
                break;
            case 'owner':
                $rank = 4;
                break;
            case 'mod':
            case 'moderator':
                $rank = 3;
                break;
            case 'mem':
            case 'member':
                $rank = 2;
                break;
            case 'guest':
                $rank = 1;
                break;
            case 'banned':
                $rank = 0;
                break;
            default:
                $rank = 0;
        }

        return $rank;
    }

    public function minrank($id, $command)
    {
        if (!isset($this->minranks[$command])) {
            echo 'No minrank for ' . strtoupper($command) . ' command.';
            return false;
        }

        if (in_array($id, XatVariables::getDevelopers())) {
            return true;
        }

        if (!is_numeric($this->minranks[$command])) {
            $this->minranks[$command] = $this->stringToRank($this->minranks[$command]);
        }
        
        if ($this->flagToRank($id) >= $this->minranks[$command]) {
            return true;
        }

        if (isset($this->stafflist[$id])) {
            if ($this->minranks[$command] <= $this->stafflist[$id]) {
                return true;
            }
        }

        return false;
    }
    
    public function botLang($name, $args = []): String
    {
        if (!isset($this->botlangs[$name])) {
            return 'Invalid sentence';
        }

        $search = [];
        $replace = [];
        $args = array_map('strval', $args);
        for ($i = 0; $i < sizeof($args); $i++) {
            $search[] = '$' . $i;
            $replace[] = $args[$i];
        }

        $search = array_reverse($search);
        $replace = array_reverse($replace);
        $response = str_replace($search, $replace, $this->botlangs[$name]);

        return is_string($response) ? htmlspecialchars_decode($response) : 'Invalid sentence';
    }

    public function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');

        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }

    public function stribet($inputstr, $deliLeft, $deliRight)
    {
        $posLeft = stripos($inputstr, $deliLeft) + strlen($deliLeft);
        $posRight = stripos($inputstr, $deliRight, $posLeft);
        return substr($inputstr, $posLeft, $posRight - $posLeft);
    }

    public function getChatName($param1)
    {
        $url = 'https://xat.com/xat' . $param1;
        $fgc = file_get_contents($url);
        return $this->stribet($fgc, "uname='", "';");
    }

    public function refresh($refreshing = false)
    {
        $bot = Bot::find($this->data->id);
        $this->__construct($bot, $refreshing);
    }

    public function stop($message = 'stop')
    {
        $this->stopped = $message;
    }

    public function sec2hms($sec, $padHours = false)
    {
        $hms = '';
        $days = intval($sec/86400);
        $hms .= (($padHours) ? str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' : $days . ' days, ');
        $sec-= ($days * 86400);
        $hours = intval(intval($sec) / 3600);
        $hms .= (($padHours) ? str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' : $hours . ' hours, ');
        $minutes = intval(($sec / 60) % 60);
        $hms .= str_pad($minutes, 2, '0', STR_PAD_LEFT) . ' minutes, ';
        $seconds = intval($sec % 60);
        $hms .= str_pad($seconds, 2, '0', STR_PAD_LEFT) . ' seconds';
        return $hms;
    }

    public function getCurrentSong()
    {
        if (empty($this->chatInfo['radio'])) {
            return false;
        }

        $url  = $this->chatInfo['radio'] . '7.html';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13)');
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($curl);

        if ($data == false) {
            return false;
        }
        curl_close($curl);

        $r = explode('<body>', $data);
        if (isset($r[1])) {
            $r = explode('</body>', $r[1]);
            $data = $r[0];
        }

        $infos = explode(',', $data);
        if (sizeof($infos) < 6) {
            return false;
        }

        $song      = htmlspecialchars_decode($infos[6]);
        $listeners = $infos[0];
        $max       = $infos[3];

        return ['lastCheck' => time() + 120, 'song' => $song, 'listeners' => $listeners, 'max' => $max];
    }

    public function steam($uid, $type = null)
    {
        $steam = DataAPI::get('steam_' . $uid);
        if (empty($type)) {
            if (!isset($steam['nextCheck'])) {
                $steam['nextCheck'] = '';
            }

            if (!empty($steam['nextCheck'])) {
                if (time() <= $steam['nextCheck']) {
                    return;
                }
            }
        }

        if (!empty($steam['steamid'])) {
            $ctx = stream_context_create(['http' => ['timeout' => 1]]);
            $json = json_decode(file_get_contents(
                'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=' .
                    XatVariables::getAPIKeys()['steam'] . '&steamids=' . $steam['steamid'],
                false,
                $ctx
            ), true);

            if (empty($json['response']['players'])) {
                if (!empty($type)) {
                    return $this->network->sendMessageAutoDetection(
                        $uid,
                        $this->botlang('cmd.steam.wrongsteamid'),
                        $type
                    );
                } else {
                    $steam['nextCheck'] = time() + 120;
                    DataAPI::set('steam_' . $uid, $steam);
                    return;
                }
            }

            $gameTitle = @$json['response']['players'][0]['gameextrainfo'];
            if (isset($gameTitle)
                && !empty($gameTitle)) {
                if (!empty($type)) {
                    return $this->network->sendMessageAutoDetection(
                        $uid,
                        $this->botlang('cmd.steam.isplaying', [$this->users[$uid]->getRegname(), $gameTitle]),
                        $type
                    );
                } else {
                    $steam['nextCheck'] = time() + 120;
                    DataAPI::set('steam_' . $uid, $steam);
                    return $this->network->updateUser(
                        $uid,
                        '',
                        '',
                        '',
                        '🎮 ' . $gameTitle . $this->users[$uid]->getStatusglow()
                    )
                    ;
                }
            } else {
                if (!empty($type)) {
                    return $this->network->sendMessageAutoDetection(
                        $uid,
                        $this->botlang('cmd.steam.notplaying'),
                        $type
                    );
                } else {
                    $steam['nextCheck'] = time() + 120;
                    DataAPI::set('steam_' . $uid, $steam);
                    return;
                }
            }
        } else {
            if (!empty($type)) {
                return $this->network->sendMessageAutoDetection($uid, $this->botlang('cmd.steam.nosteamid'), $type);
            } else {
                return;
            }
        }
    }

    public function spotify($uid, $type = null)
    {
        $spotify = DataAPI::get('spotify_' . $uid);

        if (empty($type)) {
            if (!isset($spotify['nextCheck'])) {
                $spotify['nextCheck'] = '';
            }

            if (!empty($spotify['nextCheck'])) {
                if (time() <= $spotify['nextCheck']) {
                    return;
                }
            }
        }

        $ctx = stream_context_create(['http' => ['timeout' => 1]]);
        $page = file_get_contents('https://xatbot.fr/spotify.php?xatid=' . $uid, false, $ctx);
        if (!empty($page)) {
            $currentTrack = json_decode($page, true);
        } else {
            $spotify['nextCheck'] = time() + 600;
            DataAPI::set('spotify_' . $uid, $spotify);

            if (!empty($type)) {
                return $this->network->sendMessageAutoDetection(
                    $uid,
                    'Error getting Spotify API',
                    $type
                );
            } else {
                return;
            }
        }

        if (isset($currentTrack['error'])) {
            Logger::getLogger()->info('[' . $this->data->id . '] --> SPOTIFY ERROR: ' . implode(' ', $currentTrack));

            $spotify['nextCheck'] = time() + 600;
            DataAPI::set('spotify_' . $uid, $spotify);

            if ($currentTrack['message'] == 'User is not listening') {
                if (!empty($type)) {
                    return $this->network->sendMessageAutoDetection(
                        $uid,
                        $this->botlang('cmd.spotify.notlistening', [$this->users[$uid]->getRegname()]),
                        $type
                    );
                } else {
                    return;
                }
            } else {
                if (!empty($type)) {
                    return $this->network->sendMessageAutoDetection(
                        $uid,
                        $currentTrack['message'],
                        $type
                    );
                } else {
                    return;
                }
            }
        } else {
            $spotify['nextCheck'] = time() + (int)ceil(
                ($currentTrack['item']['duration_ms'] - $currentTrack['progress_ms']) / 1000
            );
            DataAPI::set('spotify_' . $uid, $spotify);

            $artistsArray = [];
            $artists = $currentTrack['item']['artists'];
            for ($i = 0; $i < sizeof($artists); $i++) {
                $artistsArray[] = $artists[$i]['name'];
            }

            if (sizeof($artistsArray) == 1) {
                $artist = $artistsArray[0];
            } else {
                $artist = implode(', ', $artistsArray);
            }

            $song = $currentTrack['item']['name'];
            Logger::getLogger()->info('[' . $this->data->id . '] --> Song: ' . $artist . ' ' . $song . ' ' . $uid);

            if (!empty($type)) {
                if ($currentTrack['is_playing']) {
                    $string = $this->botlang('cmd.spotify.islistening', [$this->users[$uid]->getRegname()]);
                } else {
                    $string = $this->botlang('cmd.spotify.waslistening', [$this->users[$uid]->getRegname()]);
                }

                return $this->network->sendMessageAutoDetection($uid, $string . $artist . ' - ' . $song, $type);
            } else {
                return $this->network->updateUser(
                    $uid,
                    '',
                    '',
                    $currentTrack['item']['preview_url'],
                    '🎵 ' . $artist . ' - ' . $song . $this->users[$uid]->getStatusglow()
                )
                ;
            }
        }

        return;
    }
}
