<?php

$say = function (int $who, array $message, int $type) {
    
    $bot = ActionAPI::getBot();

    if (!$bot->minrank($who, 'say')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    unset($message[0]);
    $message = implode(' ', $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type);
    } else {
        return $bot->network->sendMessageAutoDetection($who, in_array($message[0], ['/', '#']) ? '_' . $message : $message, $type);
    }
};
