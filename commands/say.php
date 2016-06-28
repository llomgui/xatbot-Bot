<?php

$say = function ($who, $message) {

	$bot = actionAPI::getBot();
	
	unset($message[0]);
	$message = implode(' ', $message);
	
	if (empty($message)) {
		return $bot->network->sendMessage('The message cannot be empty.');
	} else {
		return $bot->network->sendMessage($message{0} == '/' ? '_' . $message : $message);
	}
};