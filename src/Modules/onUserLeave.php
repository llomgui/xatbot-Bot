<?php

use OceanProject\API\DataAPI;

$onUserLeave = function (int $who) {

    $bot  = OceanProject\API\ActionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    // Auto gamebot?
    if ($who == 804) {
        if (DataAPI::isSetVariable('bot') && (DataAPI::get('bot') == true)) {
            $bot->network->sendMessage('!bot');
            usleep(500000);
            $bot->network->sendMessage('!start');
        }
    }

    unset($bot->users[$who]);

    if (DataAPI::isSetVariable('away_' . $who)) {
        DataAPI::unSetVariable('away_' . $who);
    }

    if (DataAPI::isSetVariable('joined_' . $who)) {
        DataAPI::unSetVariable('joined_' . $who);
    }

    if (DataAPI::isSetVariable('spotify_' . $who)) {
        DataAPI::unSetVariable('spotify_' . $who);
    }

    if (DataAPI::isSetVariable('kickAFK_' . $who)) {
        DataAPI::unSetVariable('kickAFK_' . $who);
    }

    if (DataAPI::isSetVariable('lastMessage_' . $who)) {
        DataAPI::unSetVariable('lastMessage_' . $who);
    }

    if (DataAPI::isSetVariable('isAutotemp_' . $who)) {
        DataAPI::unSetVariable('isAutotemp_' . $who);
    }

    DataAPI::set('left_' . $who, time());

    return;
};
