<?php

$dx = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'dx')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1]) || $message[1] <= 0) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !dx [days]', $type, true);
    }

    $days = round($message[1]);
    $xats = number_format(round($message[1] * 13));
    $bot->network->sendMessageAutoDetection(
        $who,
        $bot->botlang(
            'cmd.xd',
            [
                $days,
                $days > 1 ? 'days' : 'day', $xats, 'xats'
            ]
        ),
        $type
    );
};
