<?php

$guestself = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'guestself')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    if (!$bot->botHasPower(32)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['guestself']), $type);
    }

    $bot->network->sendMessage('/g');
};
