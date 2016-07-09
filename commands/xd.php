<?php

$xd = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1]) || $message[1] == 0) {
        if ($type == 1) {
            $type = 2;
        }

        return $bot->network->sendMessageAutoDetection($who, 'Usage: !xd [xats]', $type);
    }
	
	$xats = round($message[1]);
	$days = round($message[1] / 13);
	
	$bot->network->sendMessageAutoDetection($who, $xats . ' ' . ($message[1] > 1 ? 'xats' : 'xat') . ' equal ' . $days . ' ' . ($days == 1 ? 'day' : 'days') , $type);
};
