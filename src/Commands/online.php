<?php

use xatbot\API\DataAPI;

$online = function (int $who, array $message, int $type) {
    
    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'online')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !online [regname/xatid/volunteers/chatstaff]',
            $type,
            true
        );
    }

    if (strtolower($message[1]) == 'xat' || $message[1] == '42') {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.online.42'),
            $type
        );
    }

    if (!is_numeric($message[1]) && !in_array(strtolower($message[1]), ['volunteers', 'chatstaff'])) {
        $ctx = stream_context_create(['http' => ['timeout' => 1]]);
        $fgc = file_get_contents('http://xat.me/x?name=' . $message[1], false, $ctx);
        $res = (!empty($fgc) ? $fgc : 0);
    }

    if (isset($res) && $res != 0) {
        $bot->network->sendFriendList('10101 ' . $res);
        DataAPI::set('online_command', ['who' => $who, 'type' => $type]);
        return;
    } elseif (!isset($res)) {
        $message[1] = strtolower($message[1]);
        if ($message[1] == 'volunteers') {
            $volunteers = xatbot\Bot\XatVariables::getVolunteers();

            $ids = [];
            for ($i = 0; $i < sizeof($volunteers); $i++) {
                $ids[] = $volunteers[$i]['xatid'];
            }

            $string = implode(' ', $ids);
            $bot->network->sendFriendList('10101 ' . $string);
            DataAPI::set('online_command', ['who' => $who, 'type' => $type]);
            return;
        } elseif ($message[1] == 'chatstaff') {
            $ids = [];
            if (sizeof($bot->stafflist) == 0) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'There is no staff added on your bot.',
                    $type
                );
            }
            foreach ($bot->stafflist as $id => $data) {
                $ids[] = $id;
            }
            $string = implode(' ', $ids);
            $bot->network->sendFriendList('10101 ' . $string);
            DataAPI::set('online_command', ['who' => $who, 'type' => $type]);
            return;
        } else {
            $bot->network->sendFriendList('10101 ' . $message[1]);
            DataAPI::set('online_command', ['who' => $who, 'type' => $type]);
            return;
        }
    }
};
