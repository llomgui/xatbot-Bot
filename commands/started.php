<?php

$started = function (int $who, array $message, int $type) {

	$bot = actionAPI::getBot();

	if (!$bot->minrank($who, 'started')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }
	
	$started = time() - $bot->started;
	
	$bot->network->sendMessageAutoDetection($who, 'I was started ' . $bot->secondsToTime($started) . ' ago.', $type);
};
