<?php

use xatbot\Utilities;
use xatbot\Models\Staff;
use xatbot\Models\Minrank;

$staff = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'staff')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !in_array($message[1], ['add', 'remove', 'rm'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !staff [add/remove] [xatid] [rank]',
            $type
        );
    }
    
    switch ($message[1]) {
        case 'add':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                if (isset($message[3]) && !empty($message[3])) {
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
                      $rank = strtolower($message[3]);
                    if ($rank == "botowner") {
                        $rank = "Bot Owner";
                    }
                                           
                    if (!in_array(ucfirst($rank), Minrank::pluck('name')->toArray())) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('minrank.minranknotvalid'),
                            $type
                        );
                    }
                      
                    foreach ($bot->stafflist as $key => $value) {
                        if ($key == $message[2]) {
                            return $bot->network->sendMessageAutoDetection(
                                $who,
                                $bot->botlang('cmd.staff.alreadyadded'),
                                $type
                            );
                        }
                    }
                      
                      $minrankID = Minrank::where('name', ucfirst($rank))->first();
                      
                      $staff = new Staff;
                      $staff->bot_id = $bot->data->id;
                      $staff->xatid = (int)$message[2];
                      $staff->regname = $regname;
                      $staff->minrank_id = (int)$minrankID->id;
                      $staff->save();
                      
                      $bot->stafflist = $bot->setStafflist();
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.snitch.added', [
                            $regname,
                            $message[2],
                            strtoupper($rank)
                        ]),
                        $type
                    );
                }
            }
            break;
        case 'remove':
        case 'rm':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                foreach ($bot->stafflist as $key => $value) {
                    if ($key == $message[2]) {
                        Staff::where([
                          ['xatid', '=', $message[2]],
                          ['bot_id', '=', $bot->data->id]
                        ])->delete();
                        $bot->stafflist = $bot->setStafflist();
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('cmd.snitch.removed', [
                                $message[2]
                            ]),
                            $type
                        );
                    }
                }
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.staff.notfound'),
                    $type
                );
            }
            break;
    }
};
