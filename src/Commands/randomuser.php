<?php

$randomuser = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'randomuser')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $random = array_rand($bot->users);
    $bot->network->sendMessageAutoDetection($who, $bot->users[$random]->getNick(), $type);
};
