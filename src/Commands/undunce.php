<?php

$undunce = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'undunce')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->botHasPower(158)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['dunce']), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !undunce [regname/xatid]', $type, true);
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
        if (!$user->isDunced()) {
            return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.unbadge.notdunced'), $type);
        }

        $bot->network->ban($user->getID(), 0, $reason ?? '', 'gd');
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.unbadge.nowundunced'), $type);
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type);
    }
};
