<?php

$started = function (int $who, array $message, int $type) {

	$bot = actionAPI::getBot();
	
	$started = time() - $bot->started;
	
	$bot->network->sendMessageAutoDetection($who, 'I was started ' . $bot->secondsToTime($started) . ' ago.', $type);
};
