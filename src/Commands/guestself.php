<?php

$guestself = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'guestself')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->botHasPower(32)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['guestself']), $type);
    }

    $bot->network->sendMessage('/g');
    $bot->network->sendMessageAutoDetection($who, 'I am now a guest!', $type, true);
};
