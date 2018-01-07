<?php

$started = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'started')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    $started = time() - $bot->started;
    
    $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.started', [$bot->secondsToTime($started)]), $type);
};
