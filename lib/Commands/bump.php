<?php

$bump = function (int $who, array $message, int $type) {

    $bot = ActionAPI::getBot();

    if (!$bot->minrank($who, 'bump')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->botHasPower(75)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['bump']), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !bump [regname/xatid] [message]', $type, true);
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
        if (isset($message[2])) {
            $reason = implode(' ', array_slice($message, 2));
        }

        $bot->network->sendPrivateConversation($user->getID(), '(bump) ' . ($message2 ?? ''));
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type);
    }
};
