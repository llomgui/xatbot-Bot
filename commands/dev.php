<?php

$dev = function ($who, $message) {

	if ($who != 1000000000) {
		return;
	}

	$bot = actionAPI::getBot();

	switch ($message[1]) {

		case 'reload':
			reloadExtensions();
			$bot->network->sendMessage('Extensions reloaded!');
			break;
	}

};