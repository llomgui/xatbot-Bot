<?php

use OceanProject\Models\Bot;
use OceanProject\IPC;
use OceanProject\Utilities;

$xatbot = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!in_array($who, ['412345607'])) {
        return $bot->network->sendMessageAutoDetection($who, 'Only xatbot staff can use this command.', $type);
    }

    if (!isset($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who, 
            'Usage: !xatbot [start/stop/restart/check/botid/getpaid]', 
            $type
        );
    }

    switch ($message[1]) {
        case 'start':
        case 'stop':
        case 'restart':
            if (empty($message[2]) || !is_numeric($message[2])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !xatbot ' . $message[1] . ' [botid]',
                    $type
                );
            }

            $foo = Bot::find($message[2]);
            if ($foo == false) {
                return $bot->network->sendMessageAutoDetection($who, 'This botid does not exist!', $type);
            }

            $server = $foo->server->name;
            if (IPC::init() === false) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'The socket cannot be created, please contact an administrator!',
                    $type
                );
            }

            if (IPC::connect(strtolower($server . '.sock')) === false) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'The socket is offline, please contact an administrator!',
                    $type
                );
            }

            IPC::write(sprintf("%s %d", $message[1], $message[2]));
            IPC::close();

            return $bot->network->sendMessageAutoDetection(
                $who,
                'Your bot is ' . $message[1] . (($message[1] == 'stop') ? 'ped' : 'ed') . '!',
                $type
            );

            break;

        case 'check':
            if (empty($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !xatbot check [chatname]', $type);
            }
            
            $ctx = stream_context_create(['http' => ['timeout' => 1]]);
            $url = file_get_contents('http://xat.com/' . $message[2] . '?Ocean=' . time(), false, $ctx);
            $botid = Utilities::getBetween($url, '<meta property="xat:bot" content="', '" />');

            $url = 'http://xat.com/web_gear/chat/roomid.php?d=' . $message[2];
            $fgc = json_decode(file_get_contents($url, false, $ctx), true);

            if (!isset($fgc['id']) || !is_numeric($fgc['id'])) {
                $bot->network->sendMessageAutoDetection(
                    $who,
                    'The chat "'.$message[2].'" doesn\'t exist on xat.',
                    $type
                );
                return;
            }

            if (empty($botid)) {
                $string = 'Bot power is not assigned on the room "'.ucfirst($message[2]).'". To assign, click';
                $string .= ' the power bot and click "assign", then configure it with the id 10101 in xat settings.';
                return $bot->network->sendMessageAutoDetection($who, $string, $type);
            }

            if ($botid != 10101) {
                $string = 'The bot power is not set correctly. (Current xat ID: '.$botid.') If you want your bot to ';
                $string .= 'work, you must change the bot id to this id 10101.';
                return $bot->network->sendMessageAutoDetection($who, $string, $type);
            }

            $bot->network->sendMessageAutoDetection(
                $who,
                'The power bot is configured correctly to the room "'.ucfirst($message[2]).'".',
                $type
            );
            break;

        case 'botid':
            if (empty($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !xatbot botid [chat]', $type);
            }

            if (!is_numeric($message[2])) {
                $foo = Bot::where('chatname', $message[2])->get();
            } else {
                $foo = Bot::where('chatid', $message[2])->get();
            }

            if (sizeof($foo) > 0) {
                $botid = $foo[0]->id;
                $server = $foo[0]->server->name;
                return $bot->network->sendMessageAutoDetection($who, 'Botid: ' . $botid . ' Server: ' . $server, $type);
            } else {
                return $bot->network->sendMessageAutoDetection($who, 'I have no bot assigned to this chat.', $type);
            }

            break;

        case 'getpaid':
            return $bot->network->sendMessageAutoDetection($who, 'Not available yet!', $type);
            break;
        
        default:
            $bot->network->sendMessageAutoDetection($who, 'Usage: !xatbot [start/stop/restart/check/botid/getpaid]', $type);
            break;
    }
};