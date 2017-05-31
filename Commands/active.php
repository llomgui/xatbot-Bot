<?php

$active = function (int $who, array $message, int $type) {

    $bot  = OceanProject\Bot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'active')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $now  = time();
    $userTime = $now - DataAPI::get('active_' . $who);
    $displayName = $bot->users[$who]->isRegistered() ? $bot->users[$who]->getRegname() . '(' .
    	$bot->users[$who]->getID() . ')'  : $bot->users[$who]->getID();

    $bot->network->sendMessageAutoDetection($who, $displayName . ' has been at this chat (while I was here) for: ' .
    	$bot->secondsToTime($userTime), $type);
};
