<?php

$active = function (int $who, array $message, int $type) {

    $bot  = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'active')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        $user = $bot->users[$who];
    } else {
        foreach ($bot->users as $id => $object) {
            if (is_object($object)) {
                if (strtolower($object->getRegname()) == strtolower($message[1]) || $id == $message[1]) {
                    $user = $object;
                    break;
                }
            }
        }
    }

    if (!isset($user)) {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type);
    } else {
        $now  = time();
        $userTime = $now - xatbot\API\DataAPI::get('active_' . $user->getID());
        $displayName = $user->isRegistered() ? $user->getRegname() . '(' . $user->getID() . ')' : $user->getID();

        $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.active.string', [$displayName, $bot->secondsToTime($userTime)]),
            $type
        );
    }
};
