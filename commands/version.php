<?php

$version = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();
	
    $bot->network->sendMessageAutoDetection($who, 'Script Version is: [03/21/2017]', $type);
};
