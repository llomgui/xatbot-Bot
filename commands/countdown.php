<?php

$countdown = function (int $who, array $message, int $type) {

	$bot = actionAPI::getBot();

	$releaseTime = xatVariables::getReleaseTime();

	if ($releaseTime > 1) {
		$message = 'The new power will be sold in ' . gmdate("H:i:s", $releaseTime - time()) . '.';
	} else {
		$message = 'The new power is already released/sold out, or Admins did not put a countdown on the xat banner.';
	}

	$bot->network->sendMessageAutoDetection($who, $message, $type);
};