<?php

$dx = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'dx')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !dx [days]', $type, true);
    }

    $days = round($message[1]);
    $xats = round($message[1] * 13);
    $bot->network->sendMessageAutoDetection(
        $who, $bot->botlang('cmd.xd', [$days, $days > 1 ? 'days' : 'day', $xats, 'xats']), $type
    );
};
