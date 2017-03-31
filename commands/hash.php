<?php

$hash = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'hash')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !hash [algorithm] [text]', $type, true);
    }
    
    if (in_array(strtolower($message[1]), hash_algos())) {
        $bot->network->sendMessageAutoDetection($who, hash(strtolower($message[1]), $message[2]), $type, true);
    }
};
