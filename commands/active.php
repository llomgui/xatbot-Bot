<?php

$active = function ($who, $message, $type) {

    $bot  = actionAPI::getBot();
	
    $now  = time();
	$activeTime = $now - dataAPI::get($who . '_active');

    $bot->network->sendMessageAutoDetection($who, 'This user has been on this chat for ' . $bot->secondsToTime($activeTime) . '.', $type);
};
