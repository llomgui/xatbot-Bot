<?php

$dice = function ($who, $message, $type) {

    $bot = actionAPI::getBot();
	
    $bot->network->sendMessageAutoDetection($who, 'I rolled ' . rand(1,6), $type);
};
