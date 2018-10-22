<?php

use xatbot\API\DataAPI;
use xatbot\Bot\XatHangman;

$hangman = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'hangman')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    DataAPI::set('hangman_' . $who, new XatHangman($bot, 'arachnid', $who));
    $bot->network->sendMessageAutoDetection($who, 'Hangman has started!', $type, true);
};
