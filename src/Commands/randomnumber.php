<?php

$randomnumber = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'randomnumber')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !randomnumber [maxNumber]', $type, true);
    } else {
        $maxNumber = (int)$message[1];
    }

    if ($maxNumber == 9223372036854775807) {
        return $bot->network->sendMessageAutoDetection($who, 'I am in a 64-bit environment.', $type, true);
    }

    $number = rand(0, $maxNumber);

    $bot->network->sendMessageAutoDetection($who, $number, $type);
};
