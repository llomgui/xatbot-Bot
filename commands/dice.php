<?php

$dice = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'dice')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
	
    $bot->network->sendMessageAutoDetection($who, 'I rolled ' . rand(1,6), $type);
};
