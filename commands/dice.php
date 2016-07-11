<?php

use Ocean\Xat\API\ActionAPI;

$dice = function ($who, $message, $type) {

    $bot = ActionAPI::getBot();

    $bot->network->sendMessageAutoDetection($who, 'I rolled ' . rand(1, 6), $type);
};
