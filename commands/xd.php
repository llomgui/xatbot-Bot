<?php

$xd = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'xd')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1]) || $message[1] == 0) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !xd [xats]', $type, true);
    }

    $xats = round($message[1]);
    $days = round($message[1] / 13);

    $bot->network->sendMessageAutoDetection($who, $xats . ' ' . ($message[1] > 1 ? 'xats' : 'xat') . ' equal ' . $days . ' ' . ($days == 1 ? 'day' : 'days'), $type);
};
