<?php

$randomnumber = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'randomnumber')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !randomnumber [maxNumber]', $type, true);
    } else {
        $maxNumber = $message[1];
    }

    $number = rand(0, $maxNumber);

    $bot->network->sendMessageAutoDetection($who, $number, $type);
};
