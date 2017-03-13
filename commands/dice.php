<?php

$dice = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();
	
    $bot->network->sendMessageAutoDetection($who, 'I rolled ' . rand(1,6), $type);
};
