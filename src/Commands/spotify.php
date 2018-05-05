<?php

use xatbot\API\ActionAPI;
use xatbot\API\DataAPI;
use xatbot\Bot\XatVariables;
use Illuminate\Database\Capsule\Manager as Capsule;

$spotify = function (int $who, array $message, int $type) {

    $bot = ActionAPI::getBot();

    if (!$bot->minrank($who, 'spotify')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!DataAPI::isSetVariable('spotify_' . $who)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.spotify.pleaserefresh'), $type);
    }

    return $bot->spotify($who, $type);
};
