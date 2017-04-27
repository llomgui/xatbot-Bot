<?php

$onUserJoined = function (int $who, array $array) {

    $bot = actionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    $bot->users[$who] = new xatUser($array);
    $user = $bot->users[$who];


    if ($user->isAway()) {
        dataAPI::set('away_' . $who, true);
    }

    if ($user->isRegistered() && !$user->wasHere() && !dataAPI::is_set('away_' . $who)) {
        $bot->network->sendTickle($who);
    }

    if (!$user->wasHere() && !dataAPI::is_set('away_' . $who) && !dataAPI::is_set('joined_' . $who)) {
        if (!empty($bot->botData['autowelcome'])) {
            if ($bot->botData['toggleautowelcome'] == 'pc') {
                $bot->network->sendPrivateConversation($who, $bot->botData['autowelcome']);
            } else if ($bot->botData['toggleautowelcome'] == 'pm') {
                $bot->network->sendPrivateMessage($who, $bot->botData['autowelcome']);
            }
        }
    }

    if (!dataAPI::is_set('joined_' . $who)) {
        dataAPI::set('joined_' . $who, true);
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

    if (dataAPI::is_set('gamebanrelog_' . $who) && !$user->isGamebanned()) {
        dataAPI::un_set('gamebanrelog_' . $who);
    }
        
    if ($user->isGamebanned() && $bot->botData['gameban_unban'] == 2) {
        if (!dataAPI::is_set('gamebanrelog_' . $who)) {
            dataAPI::set('gamebanrelog_' . $who, 0);
        } else {
            dataAPI::set('gamebanrelog_' . $who, dataAPI::get('gamebanrelog_' . $who) + 1);
        }
        if (dataAPI::get('gamebanrelog_' . $who) >= 2) {
            dataAPI::un_set('gamebanrelog_' . $who);
            $powers = xatVariables::getPowers();
            $bot->network->unban($who);
            $bot->network->sendMessage("{$user->getRegname()} signed out and in twice to get unbanned from the gameban '{$powers[$array['w']]['name']}'.");
        }
    }

    if ($user->isGuest() && $user->getNick()) {
        $member = false;

        switch ($bot->botData['automember']) {
            case 'sub':
                $member = ($user->hadDays());
                break;

            case 'reg':
                $member = ($user->isRegistered());
                break;

            case 'notoon':
                if (!in_array($user->getNick(), xatVariables::getDefaultName())) {
                    $member = true;
                }
                break;

            case 'maths':
                $foo = rand(5,20);
                $bar = rand(4,19);
                dataAPI::set('automember_' . $who, $foo + $bar);
                $bot->network->sendPrivateConversation($who, 'Answer that question to be a member: ' . $foo . ' + ' . $bar);
                break;

            case 'all':
                $member = true;
                break;
            
            default:
                $member = false;
                break;
        }

        if ($member) {
            $bot->network->changeRank($who, 'member');
        }
    }
    
    return;
};
