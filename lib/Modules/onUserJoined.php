<?php

use OceanProject\Bot\XatUser;
use OceanProject\Bot\API\DataAPI;
use OceanProject\Bot\XatVariables;

$onUserJoined = function (int $who, array $array) {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    $bot->users[$who] = new XatUser($array);
    $user = $bot->users[$who];

    if ($user->isAway()) {
        DataAPI::set('away_' . $who, true);
    }

    if ($user->isRegistered() && !$user->wasHere() && !DataAPI::isSetVariable('away_' . $who)) {
        $bot->network->sendTickle($who);
    }

    if (!$user->wasHere() && !DataAPI::isSetVariable('away_' . $who) && !DataAPI::isSetVariable('joined_' . $who)) {
        if (!empty($bot->data->autowelcome)) {
            if ($bot->data->toggleautowelcome == 'pc') {
                $bot->network->sendPrivateConversation($who, $bot->data->autowelcome);
            } else if ($bot->data->toggleautowelcome == 'pm') {
                $bot->network->sendPrivateMessage($who, $bot->data->autowelcome);
            }
        }
    }

    if (!DataAPI::isSetVariable('joined_' . $who)) {
        DataAPI::set('joined_' . $who, true);
    }

    if (!DataAPI::isSetVariable('active_' . $who)) {
        DataAPI::set('active_' . $who, time());
    } else {
        if (DataAPI::isSetVariable('left_' . $who)) {

            if (DataAPI::get('left_' . $who) < time() - 30) {
                DataAPI::set('active_' . $who, time());
            }

            DataAPI::unSetVariable('left_' . $who);
        }
    }

    if (DataAPI::isSetVariable('gamebanrelog_' . $who) && !$user->isGamebanned()) {
        DataAPI::unSetVariable('gamebanrelog_' . $who);
    }
        
    if ($user->isGamebanned() && $bot->data->gameban_unban == 2) {
        if (!DataAPI::isSetVariable('gamebanrelog_' . $who)) {
            DataAPI::set('gamebanrelog_' . $who, 0);
        } else {
            DataAPI::set('gamebanrelog_' . $who, DataAPI::get('gamebanrelog_' . $who) + 1);
        }
        if (DataAPI::get('gamebanrelog_' . $who) >= 2) {
            DataAPI::unSetVariable('gamebanrelog_' . $who);
            $powers = XatVariables::getPowers();
            $bot->network->unban($who);
            $bot->network->sendMessage("{$user->getRegname()} signed out and in twice to get unbanned from the gameban '{$powers[$array['w']]['name']}'.");
        }
    }

    if (sizeof($bot->badwords) > 0) {
        for ($i = 0; $i < sizeof($bot->badwords); $i++) { 
            if (strpos(strtolower($user->getNick()), strtolower($bot->badwords[$i]['badword']))) {

                DataAPI::set(
                    'modproof',
                    'User: ' . ((!is_null($regname)) ? $regname . ' (' . $who . ')' : $who) . ' Nick: ' . $user->getNick()
                );

                switch ($bot->badwords[$i]['method']) {
                    case 'ban':
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !');
                        break;

                    case 'kick':
                        return $bot->network->kick($who, 'Do not have inapp words in your name/status :o !');
                        break;

                    case 'dunce':
                        return $bot->network->ban($who, 0, 'Do not have inapp words in your name/status :o !', 'gd');
                        break;

                    case 'zap':
                        return $bot->network->kick($who, 'Do not have inapp words in your name/status :o !', '#rasberry#bump');
                        break;

                    case 'reverse':
                        return $bot->network->ban($who, $hours, 'Do not have inapp words in your name/status :o !', 'g', 176);
                        break;

                    case 'yellowcard':
                        return $bot->network->ban($who, 0, 'Do not have inapp words in your name/status :o !', 'gy');
                        break;

                    case 'badge':
                        return $bot->network->sendPrivateConversation($who, '/nb' . 'Do not have inapp words in your name/status :o !');
                        break;

                    case 'naughtystep':
                        return $bot->network->ban($who, 0, 'Do not have inapp words in your name/status :o !', 'gn');
                        break;

                    case 'snakeban':
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !', 'g', 134);
                        break;

                    case 'spaceban':
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !', 'g', 136);
                        break;

                    case 'matchban':
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !', 'g', 140);
                        break;

                    case 'codeban':
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !', 'g', 162);
                        break;

                    case 'mazeban':
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !', 'g', 152);
                        break;

                    case 'slotban':
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !', 'g', 236);
                        break;
                    
                    default:
                        return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not have inapp words in your name/status :o !');
                        break;
                }
            }
        }
    }

    if ($user->isGuest() && $user->getNick()) {
        $member = false;

        switch ($bot->data->automember) {
            case 'sub':
                $member = ($user->hadDays());
                break;

            case 'reg':
                $member = ($user->isRegistered());
                break;

            case 'notoon':
                if (!in_array($user->getNick(), XatVariables::getDefaultName())) {
                    $member = true;
                }
                break;

            case 'maths':
                $foo = rand(5,20);
                $bar = rand(4,19);
                DataAPI::set('automember_' . $who, $foo + $bar);
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
