<?php

use OceanProject\Utilities;
use OceanProject\Models\AutoBan;

$autoban = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'autoban')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !in_array($message[1], ['add', 'remove', 'rm', 'ls', 'list'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !autoban [add/remove/list] [xatid] [method] [hours]',
            $type
        );
    }
    
    $methods  = [
        'ban'         => 'Ban',
        'snakeban'    => 'Snakeban',
        'spaceban'    => 'Spaceban',
        'matchban'    => 'Matchban',
        'codeban'     => 'Codeban',
        'mazeban'     => 'Mazeban',
        'slotban'     => 'Slotban',
        'reverseban'  => 'Reverseban',
        'zipban'      => 'Zipban'
    ];
    
    switch ($message[1]) {
        case 'add':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                if (isset($message[3]) && !empty($message[3])) {
                    if (isset($message[4]) && !empty($message[4]) && is_int((int)$message[4])) {
                        foreach ($bot->autobans as $autoban) {
                            if ($autoban['xatid'] == $message[2]) {
                                return $bot->network->sendMessageAutoDetection(
                                    $who,
                                    $bot->botlang('user.alreadyadded'),
                                    $type
                                );
                            }
                        }
                        
                        if (Utilities::isValidXatID($message[2])) {
                            $regname = Utilities::isXatIDExist($message[2]);
                            if (!$regname) {
                                return $bot->network->sendMessageAutoDetection(
                                    $who,
                                    $bot->botlang('xatid.notexist'),
                                    $type
                                );
                            }
                        } else {
                            return $bot->network->sendMessageAutoDetection(
                                $who,
                                $bot->botlang('xatid.notvalid'),
                                $type
                            );
                        }
                        
                        if (!array_key_exists(strtolower($message[3]), $methods)) {
                            return $bot->network->sendMessageAutoDetection(
                                $who,
                                $bot->botlang('method.notvalid'),
                                $type
                            );
                        }
                        
                        if ($message[4] < 0 || !is_numeric($message[4])) {
                            return $bot->network->sendMessageAutoDetection(
                                $who,
                                $bot->botlang('hours.notvalid'),
                                $type
                            );
                        }
                        
                        $autoban = new AutoBan;
                        $autoban->bot_id = $bot->data->id;
                        $autoban->xatid = (int)$message[2];
                        $autoban->regname = $regname;
                        $autoban->method = strtolower($message[3]);
                        $autoban->hours = (int) $message[4];
                        $autoban->save();
                        
                        $bot->autobans = $bot->setAutobanList();
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('user.addedtolist', [$regname, $message[2]]),
                            $type
                        );
                    }
                }
            }
            break;
        case 'rm':
        case 'remove':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                foreach ($bot->autobans as $autoban) {
                    if ($autoban['xatid'] == $message[2]) {
                        AutoBan::where([
                          ['xatid', '=', (int)$message[2]],
                          ['bot_id', '=', $bot->data->id]
                        ])->delete();
                        $bot->autobans = $bot->setAutobanList();
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('user.removedtolist', [$message[2]]),
                            $type
                        );
                    }
                }
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('user.notinlist'),
                    $type
                );
            }
            break;
        case 'ls':
        case 'list':
            $autobanList = [];
            foreach ($bot->autobans as $autoban) {
                $autobanList[] = $autoban['regname'] . ' [' . $autoban['xatid'] . ']';
            }
            
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Autoban list: ' . (sizeof($autobanList) == 0 ? "None" : implode(', ', $autobanList)) . '.',
                $type
            );
            break;
    }
};
