<?php

use OceanProject\Utilities;
use OceanProject\Models\AutoTemp;

$autotemp = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'autotemp')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    if (!isset($message[1]) || empty($message[1]) || !in_array($message[1], ['add', 'remove', 'rm', 'list', 'ls'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !autotemp [add/remove/list] [xatid] [hours]',
            $type
        );
    }

    switch ($message[1]) {
        case 'add':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                if (isset($message[3]) && !empty($message[3]) && is_int((int)$message[3])) {
                    foreach ($bot->autotemps as $autotemp) {
                        if ($autotemp['xatid'] == $message[2]) {
                            return $bot->network->sendMessageAutoDetection(
                                $who,
                                'Sorry, this user is already added.',
                                $type
                            );
                        }
                    }

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

                    $autotemp = new AutoTemp;
                    $autotemp->bot_id  = $bot->data->id;
                    $autotemp->xatid   = (int)$message[2];
                    $autotemp->regname = $regname;
                    $autotemp->hours   = (int)$message[3];
                    $autotemp->save();
                    $bot->autotemps = $bot->setAutotempList();
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $regname . '(' . $message[2] . ')has been added to the list!',
                        $type
                    );
                }
            }
            break;

        case 'remove':
        case 'rm':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                foreach ($bot->autotemps as $autotemp) {
                    if ($autotemp['xatid'] == $message[2]) {
                        AutoTemp::where([
                            ['xatid', '=', (int)$message[2]],
                            ['bot_id', '=', $bot->data->id]
                        ])->delete();
                        $bot->autotemps = $bot->setAutotempList();
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $message[2] . ' has been removed from the list!',
                            $type
                        );
                    }
                }
                return $bot->network->sendMessageAutoDetection($who, 'I could not find this user in the list.', $type);
            }
            break;

        case 'list':
        case 'ls':
            $string = '';
            foreach ($bot->autotemps as $autotemp) {
                $string .= '[' . $autotemp['regname'] . '(' . $autotemp['xatid'] . ')' . $autotemp['hours'] . ' hours]';
            }
            return $bot->network->sendMessageAutoDetection($who, $string, $type);
            break;
    }

    return $bot->network->sendMessageAutoDetection($who, 'Usage: !autotemp [add/remove/list] [xatid] [hours]', $type);
};
