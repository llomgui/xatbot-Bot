<?php

$version = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();
	
    $bot->network->sendMessageAutoDetection($who, 'Script Updated.', $type);
};
