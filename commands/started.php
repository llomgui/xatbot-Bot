<?php

use Ocean\Xat\API\ActionAPI;

$started = function ($who, $message, $type) {

    $bot = ActionAPI::getBot();

    $started = time() - $bot->started;

	$bot->network->sendMessageAutoDetection($who, 'I was started ' . $bot->secondsToTime($started) . ' ago.', $type);
};