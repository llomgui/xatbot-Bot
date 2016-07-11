<?php

use Ocean\Xat\API\ActionAPI;

$active = function ($who, $message, $type) {

    $bot  = ActionAPI::getBot();
    $now  = time();
    $userTime = $now - DataAPI::get($who . '_active');
    $displayName = $bot->users[$who]->isRegistered() ? $bot->users[$who]->getRegname() . '(' . $bot->users[$who]->getID() . ')'  : $bot->users[$who]->getID();

    $bot->network->sendMessageAutoDetection($who, $displayName . ' has been at this chat (while I was here) for: ' . $bot->secondsToTime($userTime), $type);
};
