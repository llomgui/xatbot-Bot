<?php

$active = function ($who, $message, $type) {

    $bot  = actionAPI::getBot();
    $now  = time();
    $userTime = $now - dataAPI::get($who . '_active');
    $displayName = $bot->users[$who]->isRegistered() ? $bot->users[$who]->getRegname() . '(' . $bot->users[$who]->getID() . ')'  : $bot->users[$who]->getID();

    $bot->network->sendMessageAutoDetection($who, $displayName . ' has been at this chat (while I was here) for: ' . $bot->secondsToTime($userTime), $type);
};
