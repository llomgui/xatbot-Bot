<?php

$dev = function ($who, $message, $type) {

	if ($who != 1000000000) {
		return;
	}

	$bot = actionAPI::getBot();

	switch ($message[1]) {

		case 'reload':
			reloadExtensions();
			$bot->network->sendMessageAutoDetection($who, 'Extensions reloaded!', $type);
			break;
	}

};