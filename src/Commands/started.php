<?php

$started = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'started')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    $started = time() - $bot->started;
    
    $bot->network->sendMessageAutoDetection($who, 'I was started ' . $bot->secondsToTime($started) . ' ago.', $type);
};
