<?php

$refresh = function (int $who, array $message, int $type) {
    
    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'refresh')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $bot->refresh();

    $bot->network->sendMessageAutoDetection($who, 'Refreshing...', $type);
};
