<?php

$unban = function (int $who, array $message, int $type) {

    $bot = ActionAPI::getBot();

    if (!$bot->minrank($who, 'unban')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !unban [regname/xatid]', $type, true);
    }

    if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
        $user = $bot->users[$message[1]];
    } else {
        foreach ($bot->users as $id => $object) {
            if (is_object($object) && strtolower($object->getRegname()) == strtolower($message[1])) {
                $user = $object;
                break;
            }
        }
    }

    if (isset($user)) {
        $bot->network->unban($user->getID());
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type);
    }
};
