<?php

$hash = function (int $who, array $message, int $type) {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'hash')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !hash [algorithm] [text]', $type, true);
    }
    
    if (in_array(strtolower($message[1]), hash_algos())) {
        $bot->network->sendMessageAutoDetection($who, hash(strtolower($message[1]), $message[2]), $type, true);
    }
};
