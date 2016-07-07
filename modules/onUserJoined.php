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
    
    if (!dataAPI::is_set($who . '_joined')) {
        dataAPI::set($who . '_joined', false);
    }

    if (!dataAPI::is_set($who . '_active')) {
        dataAPI::set($who . '_active', time());
    }

    if (dataAPI::is_set($who . '_left')) {
        if (dataAPI::get($who . '_left') - 300 > dataAPI::get($who . '_active')) {
            dataAPI::set($who . '_active', time());
            dataAPI::un_set($who . '_left', time());
        }
    }

    if ($user->isAway() or dataAPI::get('away_' . $user->getID())) {
        dataAPI::set('away_' . $user->getID(), $user->isAway());
        return;
    }
    

    return;
};
