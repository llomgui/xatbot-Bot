<?php

$clear = function (int $who, array $message, int $type) {

	$bot = actionAPI::getBot();

	if (!$bot->minrank($who, 'clear')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

	for ($i = $bot->messageCount - 23; $i <= $bot->messageCount; $i++) {
		$bot->network->sendMessage('/d' . ($i + 1));
	}

	$bot->network->sendMessageAutoDetection($who, 'Chat is now cleared!', $type);
};