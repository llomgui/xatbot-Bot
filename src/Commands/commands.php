<?php

$commands = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'commands')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    $bot->network->sendMessageAutoDetection($who, 'https://xatbot.fr/panel/commands/' . $bot->data->id, $type);
};
