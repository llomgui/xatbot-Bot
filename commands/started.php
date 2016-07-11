<?php

use Ocean\Xat\API\ActionAPI;

$started = function ($who, $message, $type) {

    $bot = ActionAPI::getBot();

    $started = time() - $bot->started;

	$days    = floor($started / 86400);
	$hours   = floor($started / 3600);
	$minutes = floor(($started / 60) % 60);
	$seconds = $started % 60;

    $bot->network->sendMessageAutoDetection($who, 'I was started ' . sprintf("%02d days, %02d hours, %02d minutes and %02d seconds", $days, $hours, $minutes, $seconds) . ' ago.', $type);
};
