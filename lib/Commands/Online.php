<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;
use Ocean\Xat\API\DataAPI;
use Ocean\Xat\Variables;
use Ocean\Xat\API;
use Ocean\Xat\Bot;

class Online
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (empty($message[1]) || !isset($message[1])) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !online [regname/xatid/volunteers]',
                $type,
                true
            );
        }

        if ($message[1] == 'xat' || $message[1] == '42') {
            return $bot->network->sendMessageAutoDetection(
                $who,
                '42 does not appear online on friendlists, so it is impossible to determine if he is online or not.',
                $type
            );
        }

        if (!is_numeric($message[1]) && $message[1] != 'volunteers') {
            $ctx = stream_context_create(['http' => ['timeout' => 1]]);
            $fgc = file_get_contents('http://xat.me/x?name=' . $message[1], false, $ctx);
            $res = (!empty($fgc) ? $fgc : 0);
        }

        if (isset($res) && $res != 0) {
            $bot->network->sendFriendList('10101 ' . $res);
            DataAPI::set('online_command', ['who' => $who, 'type' => $type]);
            return;
        } elseif (!isset($res)) {
            if ($message[1] == 'volunteers') {
                $volunteers = Variables::getVolunteers();

                $ids = [];
                for ($i = 0; $i < sizeof($volunteers); $i++) {
                    $ids[] = $volunteers[$i]['xatid'];
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
    }
}
