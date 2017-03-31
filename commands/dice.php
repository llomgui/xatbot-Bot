<?php

$dice = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'dice')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }
	
    $bot->network->sendMessageAutoDetection($who, 'I rolled ' . rand(1,6), $type);
};
