<?php

$onUserJoined = function ($who, $array) {

    $bot = actionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    $bot->users[$who] = new User($array);
    $user = $bot->users[$who];

    if ($user->isAway()) {
        dataAPI::set('away_' . $user->getID(), true);
    }

    if ($user->isRegistered() && !$user->wasHere() && !dataAPI::is_set('away_' . $user->getID())) {
        $bot->network->sendTickle($who);
    }

    if (!dataAPI::is_set('active_' . $who)) {
        dataAPI::set('active_' . $who, time());
    } else {
        if (dataAPI::is_set('left_' . $who)) {

            if (dataAPI::get('left_' . $who) < time() - 30) {
                dataAPI::set('active_' . $who, time());
            }

            dataAPI::un_set('left_' . $who);
        }
    }
    
    if (dataAPI::is_set($who . '_gamebanrelog') && !$user->isGamebanned()) {
        dataAPI::un_set($who . '_gamebanrelog');
    }
        
    if ($user->isGamebanned() && $bot->botData['gameban_unban'] == 2) {
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
