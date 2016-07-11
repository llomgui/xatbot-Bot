<?php

$mostactive = function ($who, $message, $type) {

    $bot  = actionAPI::getBot();
    $now  = time();
    $most = ['user' => null, 'time' => 0];

    foreach ($bot->users as $user) {
        if (!is_object($user) || !dataAPI::is_set($who . '_active')) {
            continue;
        }

        $userTime = $now - dataAPI::get($who . '_active');

        if ($userTime > $most['time']) { // Maybe implement a way to show more then 1 user if activetime is equal?
            $most = ['user' => $user, 'time' => $userTime];
        }
    }

    $displayName = $most['user']->isRegistered() ? $most['user']->getRegname() . '(' . $most['user']->getID() . ')'  : $most['user']->getID();

    $bot->network->sendMessageAutoDetection($who, 'The current most active user is ' . $displayName . ' with a time of ' . $bot->secondsToTime($userTime), $type);
};
