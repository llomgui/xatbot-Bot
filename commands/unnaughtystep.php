<?php

$unnaughtystep = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

	if (!$bot->botHasPower(284)) {
        return $bot->network->sendMessageAutoDetection($who, sprintf('Sorry, but i don\'t have the power \'%s\'.', 'naughtystep'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !unnaughtystep [regname/xatid]', $type, true);
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
        if (!$user->isNaughty()) {
            return $bot->network->sendMessageAutoDetection($who, 'That user is not naughtstepped.', $type);
        }

        $bot->network->ban($user->getID(), 0, $reason ?? '', 'gn');
    } else {
        $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
    }
};
