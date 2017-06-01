<?php

use OceanProject\Bot\API\DataAPI;
use OceanProject\Bot\XatVariables;

$onApp = function (int $who, int $app, array $array) {
    $bot = OceanProject\Bot\API\ActionAPI::getBot();
    switch ($app) {
        case 10000:
            break;
        case 20010:
            if (isset($array['d']) && $who != $bot->network->logininfo['i']) {
                if (!isset($array['t']) || empty($array['t'])) {
                    if (DataAPI::isSetVariable('boards_' . $who)) {
                        DataAPI::unSetVariable('boards_' . $who);
                    }
                    return;
                }
                if ((DataAPI::isSetVariable('boards_' . $who) && (strlen($array['t']) == 0 ||
                    strlen($array['t'] == 1))) || !DataAPI::isSetVariable('boards_' . $who)) {
                    DataAPI::set('boards_' . $who, new Connect4());
                }
                $last = substr($array['t'], -1);
                if (is_numeric($last)) {
                    return $bot->network->sendPrivateConversation($who, 'The fuck you doin?');
                }
                $move = DataAPI::get('boards_' . $who)->set(ord($last) - 65);
                if ($move == 1000) {
                    DataAPI::unSetVariable('boards_' . $who);
                    return $bot->network->sendPrivateConversation($who, 'You have won.');
                } elseif ($move == 50) {
                    DataAPI::unSetVariable('boards_' . $who);
                    return $bot->network->sendPrivateConversation($who, 'You caused the game to become a draw.');
                } elseif ($move[0] == 51) {
                    DataAPI::unSetVariable('boards_' . $who);
                    $bot->network->sendPrivateConversation($who, 'I caused the game to become a draw.');
                } elseif ($move == -1000 || $move[0] == -1000) {
                    DataAPI::unSetVariable('boards_' . $who);
                    $bot->network->sendPrivateConversation($who, 'You have lost.');
                } elseif ($move == 666) {
                    $bot->network->sendPrivateConversation($who, 'Tsk tsk tsk... No cheating.');
                    return $bot->network->write('x', [
                        'i' => $app,
                        'u' => $array['d'],
                        'd' => $who,
                        't' => substr($array['t'], 0, -1)
                    ]);
                } elseif (strlen($array['t']) >= 42) {
                    DataAPI::unSetVariable('boards_' . $who);
                    return $bot->network->sendPrivateConversation(
                        $who, 'The game has ended in a draw because the board is full.'
                    );
                }
                if (is_array($move)) {
                    $move = $move[1];
                }
                $move = chr($move + 65);
                $bot->network->write('x', [
                    'i' => $app,
                    'u' => $array['d'],
                    'd' => $who,
                    't' => $array['t'] . $move,
                ]);
            }
            break;

        case 30008:
            if (isset($array['t'])) {
                switch ($array['t'][0]) {
                    case 'G':
                        $buildPacket = ['i' => 30008, 'u' => XatVariables::getXatid(), 'd' => $who, 't' => 'G'];
                        $bot->network->write('x', $buildPacket);
                        break;

                    case 'O':
                        DataAPI::set(
                            'received_trade_' . $who,
                            str_replace([',', 'undefined'], [';', '0'],substr($array['t'], 2))
                        );
                        break;

                    case 'S':
                        if ($array['t'] == 'S,1') {
                            // <x i="30008" u="xatid" d="destid" t="S,5" />
                            $buildPacket = ['i' => 30008, 'u' => XatVariables::getXatid(), 'd' => $who, 't' => 'S,5'];
                            $bot->network->write('x', $buildPacket);

                            usleep(300000);

                            // <x i="30008" u="xatid" d="destid" t="S,1" />
                            $buildPacket = ['i' => 30008, 'u' => XatVariables::getXatid(), 'd' => $who, 't' => 'S,1'];
                            $bot->network->write('x', $buildPacket);

                            usleep(300000);

                            // <x i="30008" u="xatid" d="destid" t="T,0;0;259=2|,0;0;,password" />
                            $buildPacket = [
                                'i' => 30008,
                                'u' => XatVariables::getXatid(),
                                'd' => $who,
                                't' => 'T,' . (DataAPI::isSetVariable('sent_trade_' . $who) ?
                                    DataAPI::get('sent_trade_' . $who) : '0;0;') . ',' .
                                        (DataAPI::isSetVariable('received_trade_' . $who) ?
                                    DataAPI::get('received_trade_' . $who) : '0;0;') . ',' .
                                        XatVariables::getPassword()
                            ];
                            $bot->network->write('x', $buildPacket);

                            if (DataAPI::isSetVariable('sent_trade_' . $who)) {
                                DataAPI::unSetVariable('sent_trade_' . $who);
                            }

                            if (DataAPI::isSetVariable('received_trade_' . $who)) {
                                DataAPI::unSetVariable('received_trade_' . $who);
                            }
                        }
                        break;
                }
            }

            break;
    }
};
