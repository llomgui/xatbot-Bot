<?php

$say = function ($who, $message, $type) {
    $bot = actionAPI::getBot();

    var_dump($bot->minranks);

    var_dump($bot->flagToRank($who));

    if (!$bot->minrank($who, 'say')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    unset($message[0]);
    $message = implode(' ', $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type);
    } else {
        return $bot->network->sendMessageAutoDetection($who, in_array($message[0], ['/', '#']) ? '_' . $message : $message, $type);
    }
};
