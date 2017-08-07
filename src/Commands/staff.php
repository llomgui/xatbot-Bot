<?php

use OceanProject\Utilities;
use OceanProject\Models\Staff;
use OceanProject\Models\Minrank;

$staff = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

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
                                'The xatid does not exist!',
                                $type
                            );
                        }
                    } else {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            'The xatid is not valid!',
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
                            'The minrank is not valid!',
                            $type
                        );
                    }
                      
                    foreach ($bot->stafflist as $key => $value) {
                        if ($key == $message[2]) {
                            return $bot->network->sendMessageAutoDetection(
                                $who,
                                'The user is already added!',
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
                          $regname . '(' . $message[2] . ')has been added as ' . strtoupper($rank) . '.',
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
                            $message[2] . ' has been removed from the list!',
                            $type
                        );
                    }
                }
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'I could not find this user in the list.',
                    $type
                );
            }
            break;
    }
};
