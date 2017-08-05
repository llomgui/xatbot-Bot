<?php

use OceanProject\Utilities;
use OceanProject\Models\Snitch;

$snitch = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'snitch')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    if (!isset($message[1]) || empty($message[1]) || !in_array($message[1], ['add', 'remove', 'rm'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !snitch [add/remove] [xatid]',
            $type
        );
    }

    switch ($message[1]) {
        case 'add':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                foreach ($bot->snitchlist as $snitch) {
                    if ($snitch['xatid'] == $message[2]) {
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

                  $snitch = new Snitch;
                  $snitch->bot_id  = $bot->data->id;
                  $snitch->xatid   = (int)$message[2];
                  $snitch->regname = $regname;
                  $snitch->save();
                  $bot->snitchlist = $bot->setSnitchList();
                  return $bot->network->sendMessageAutoDetection(
                      $who,
                      $regname . '(' . $message[2] . ')has been added to the list!',
                      $type
                  );
            }
            break;
        case 'remove':
        case 'rm':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                foreach ($bot->snitchlist as $snitch) {
                    if ($snitch['xatid'] == $message[2]) {
                        Snitch::where([
                          ['xatid', '=', $message[2]],
                          ['bot_id', '=', $bot->data->id]
                        ])->delete();
                        $bot->snitchlist = $bot->setSnitchList();
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
    }

    return $bot->network->sendMessageAutoDetection($who, 'Usage: !snitch [add/remove] [xatid]', $type);
};
