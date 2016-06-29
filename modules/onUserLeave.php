<?php

$onUserLeave = function ($who) {
	
	$bot  = actionAPI::getBot();

	if ($who >= 1900000000) {
		return;
	}

	unset($bot->users[$who]);

	return;
};
