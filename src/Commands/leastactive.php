<?php

use OceanProject\API\DataAPI;

$leastactive = function (int $who, array $message, int $type) {

    $bot  = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'leastactive')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $now  = time();
    $least = [
        'user' => null,
        'time' => 0
    ];

    foreach ($bot->users as $user) {
        if (!is_object($user) || !DataAPI::isSetVariable('active_' . $user->getID())) {
            continue;
        }

        $userTime = $now - DataAPI::get('active_' . $user->getID());

        if ($least['time'] == 0) {
            $least = [
                'user' => $user,
                'time' => $userTime
            ];
        } elseif ($userTime < $least['time']) {
            $least = [
                'user' => $user,
                'time' => $userTime
            ];
        }
    }

    $displayName = $least['user']->isRegistered() ?
        $least['user']->getRegname() . '(' . $least['user']->getID() . ')' :
        $least['user']->getID();

    $bot->network->sendMessageAutoDetection(
        $who,
        'The current least active user is ' . $displayName . ' with a time of ' . $bot->secondsToTime($userTime),
        $type
    );
};
