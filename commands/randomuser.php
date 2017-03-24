<?php

$randomuser = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'randomuser')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    $random = array_rand($bot->users);
    $bot->network->sendMessageAutoDetection($who, $bot->users[$random]->getNick(), $type);
};
