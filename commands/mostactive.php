<?php

$mostactive = function (int $who, array $message, int $type) {

    $bot  = actionAPI::getBot();

    if (!$bot->minrank($who, 'mostactive')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $now  = time();
    $most = ['user' => null, 'time' => 0];

    foreach ($bot->users as $user) {
        if (!is_object($user) || !dataAPI::is_set('active_' . $user->getID())) {
            continue;
        }

        $userTime = $now - dataAPI::get('active_' . $user->getID());

        if ($userTime > $most['time']) {
            $most = ['user' => $user, 'time' => $userTime];
        }
    }

    $displayName = $most['user']->isRegistered() ? $most['user']->getRegname() . '(' . $most['user']->getID() . ')'  : $most['user']->getID();

    $bot->network->sendMessageAutoDetection($who, 'The current most active user is ' . $displayName . ' with a time of ' . $bot->secondsToTime($userTime), $type);
};
