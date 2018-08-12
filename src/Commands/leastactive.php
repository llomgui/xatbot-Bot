<?php

use xatbot\API\DataAPI;

$leastactive = function (int $who, array $message, int $type) {

    $bot  = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'leastactive')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $least = ['user' => null, 'time' => 0];

    foreach ($bot->users as $user) {
        if (!is_object($user) || !DataAPI::isSetVariable('active_' . $user->getID())) {
            continue;
        }

        $userTime = DataAPI::get('active_' . $user->getID());

        if ($least['time'] == 0 || $userTime > $least['time']) {
            $least = ['user' => $user, 'time' => $userTime];
        }
    }

    $displayName = $least['user']->isRegistered() ?
        $least['user']->getRegname() . '(' . $least['user']->getID() . ')' :
        $least['user']->getID();

    $bot->network->sendMessageAutoDetection(
        $who,
        $bot->botlang('cmd.leastactive', [$displayName, $bot->secondsToTime(time() - $userTime)]),
        $type
    );
};
