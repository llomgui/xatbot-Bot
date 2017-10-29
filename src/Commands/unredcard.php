<?php

$unredcard = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'unredcard')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->botHasPower(339)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['redcard']), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !unredcard [regname/xatid]', $type, true);
    }

    if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
        $user = $bot->users[$message[1]];
    } else {
        foreach ($bot->users as $id => $object) {
            if (is_object($object)) {
                if (strtolower($object->getRegname()) == strtolower($message[1])) {
                    $user = $object;
                    break;
                }
            }
        }
    }

    if (isset($user)) {
        if (!$user->isRedCarded()) {
            return $bot->network->sendMessageAutoDetection($who, 'That user is not red carded.', $type);
        }

        $bot->network->ban($user->getID(), 0, $reason ?? '', 'gr');
        $bot->network->sendMessageAutoDetection($who, 'The user is now unredcarded.', $type);
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type);
    }
};
