<?php

$dx = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !dx [days]', $type, true);
    }

    $days = round($message[1]);
    $xats = round($message[1] * 13);

    $bot->network->sendMessageAutoDetection($who, $days . ' ' . ($days > 1 ? 'days' : 'day') . ' equals ' . $xats . ' xats', $type);
};
