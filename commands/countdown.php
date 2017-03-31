<?php

$countdown = function (int $who, array $message, int $type) {

	$bot = actionAPI::getBot();

	if (!$bot->minrank($who, 'countdown')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

	$releaseTime = xatVariables::getReleaseTime();

	if ($releaseTime > 1) {
		$message = 'The new power will be sold in ' . gmdate("H:i:s", $releaseTime - time()) . '.';
	} else {
		$message = 'The new power is already released/sold out, or Admins did not put a countdown on the xat banner.';
	}

	$bot->network->sendMessageAutoDetection($who, $message, $type);
};