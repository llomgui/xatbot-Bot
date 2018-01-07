<?php

$choose = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'choose')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    unset($message[0]);
    $message = implode(' ', $message);
    $message = preg_split('/ or /', strtolower($message), 2);

    if ((!isset($message[0]) || empty($message[0])) || (!isset($message[1]) || empty($message[1]))) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !choose [first choice] or [second choice]',
            $type,
            true
        );
    }

    $choice = rand(0, 100) > 50 ? $message[0] : $message[1];
    $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.choose.haschosen', [$choice]), $type);
};
