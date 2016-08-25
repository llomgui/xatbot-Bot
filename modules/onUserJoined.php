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
    
    /*
        TODO
        add autounban variable
        check if autounban enabled
    */
    $isGamebanned = isset($array['w']) && !in_array($array['w'], [176, 184]);//zip and reverse show up in w attribute :'(
    if (dataAPI::is_set($who . '_gamebanrelog') && !$isGamebanned) {
        dataAPI::un_set($who . '_gamebanrelog');
    }
        
    if ($isGamebanned) {
        if (!dataAPI::is_set($who . '_gamebanrelog')) {
            dataAPI::set($who . '_gamebanrelog', 0);
        } else {
            dataAPI::set($who . '_gamebanrelog', dataAPI::get($who . '_gamebanrelog') + 1);
        }
        if (dataAPI::get($who . '_gamebanrelog') >= 2) {
            dataAPI::un_set($who . '_gamebanrelog');
            $powers = xatVariables::getPowers();
            $bot->network->unban($who);
            $bot->network->sendMessage("{$user->getRegname()} signed out and in twice to get unbanned from the gameban '{$powers[$array['w']]['name']}'.");
        }
    }
    
    return;
};
