<?php

$redcard = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'redcard')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->botHasPower(339)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['redcard']), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !redcard [regname/xatid] [reason]',
            $type,
            true
        );
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
        if ($user->isRedCarded()) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('user.already', ['redcarded']),
                $type
            );
        }

        if (isset($message[2])) {
            $reason = implode(' ', array_slice($message, 1));
        }

        $bot->network->ban($user->getID(), 0, $reason ?? '', 'gy');
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type);
    }
};
