<?php

$say = function (int $who, array $message, int $type) {
    
    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'say')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    unset($message[0]);
    $message = implode(' ', $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type);
    } else {
        //$message = str_replace('/', '_/', $message);
        return $bot->network->sendMessageAutoDetection($who, '_' . $message, $type);
    }
};
