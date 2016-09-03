<?php

$clear = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	for ($i = $bot->messageCount - 23; $i <= $bot->messageCount; $i++) {
		$bot->network->sendMessage('/d' . ($i + 1));
	}

	$bot->network->sendMessageAutoDetection($who, 'Chat is now cleared!', $type);
};