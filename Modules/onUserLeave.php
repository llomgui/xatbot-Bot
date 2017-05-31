<?php

use OceanProject\Bot\API\DataAPI;

$onUserLeave = function (int $who) {

    $bot  = OceanProject\Bot\API\ActionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    unset($bot->users[$who]);

    if (DataAPI::isSetVariable('away_' . $who)) {
        DataAPI::unSetVariable('away_' . $who);
    }

    if (DataAPI::isSetVariable('joined_' . $who)) {
        DataAPI::unSetVariable('joined_' . $who);
    }

    DataAPI::set('left_' . $who, time());

    return;
};
