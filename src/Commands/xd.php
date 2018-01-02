<?php

$xd = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'xd')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1]) || $message[1] <= 0) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !xd [xats]', $type, true);
    }

    $xats = round($message[1]);
    $days = floor($message[1] / 13);

    $bot->network->sendMessageAutoDetection(
        $who,
        $bot->botlang('cmd.xd', [
                $xats,
                $xats > 1 ? 'xats' : 'xat', $days, $days == 1 ? 'day' : 'days'
            ]),
        $type
    );
};
