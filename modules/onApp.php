<?php

$onApp = function ($who, $app, $array) {
    $bot = actionAPI::getBot();
    switch ($app) {
        case 10000: //Example stub for doodle

            break;
        case 20010:
            if (isset($array['d']) && $who != $bot->network->logininfo['i']) {
                if (!isset($array['t']) || empty($array['t'])) {
                    }
                    return;
                }
                }
                $last = substr($array['t'], -1);
                if(is_numeric($last)) {
                    return $bot->network->sendPrivateConversation($who, "The fuck you doin?");
                }
                if($move == 1000) {
                    return $bot->network->sendPrivateConversation($who, "You have won.");
                } else if ($move == 50) {
                    return $bot->network->sendPrivateConversation($who, "You caused the game to become a draw.");
                } else if ($move[0] == 51) {
                } else if ($move == -1000 || $move[0] == -1000) {
                } else if ($move == 666) {
                    $bot->network->sendPrivateConversation($who, "Tsk tsk tsk... No cheating.");
                    return $bot->network->socket->write('x', [
                        'i' => $app,
                        'u' => $array['d'],
                        'd' => $who,
                        't' => substr($array['t'], 0, -1)
                    ]);
                } else if(strlen($array['t']) >= 42) {
                    return $bot->network->sendPrivateConversation($who, "The game has ended in a draw because the board is full.");
                }
                if(is_array($move)) {
                    $move = $move[1];
                }
                $move = chr($move + 65);
                $bot->network->socket->write('x', [
                    'i'	=> $app,
                    'u' => $array['d'],
                    'd' => $who,
                    't' => $array['t'] . $move,
                ]);
            }
            break;
    }
};
