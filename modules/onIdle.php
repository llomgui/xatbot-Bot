<?php

$onIdle = function ($array) {
	$bot = actionAPI::getBot();

	$bot->network->reconnect();
};