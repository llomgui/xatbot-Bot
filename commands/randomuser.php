<?php

$randomuser = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    $random = array_rand($bot->users);
    $bot->network->sendMessageAutoDetection($who, $bot->users[$random]->getNick(), $type);
};
