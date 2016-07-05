<?php

$onUserJoined = function ($who, $array) {
    
    $bot = actionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    $bot->users[$who] = new User($array);
    $user = $bot->users[$who];

    if ($user->isRegistered() && !$user->isAway() && !$user->wasHere()) {
        $bot->network->sendTickle($who);
    }

    return;
};
