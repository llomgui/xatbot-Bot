<?php

use Ocean\Bot\API\ActionAPI;
use Ocean\Bot\API\DataAPI;

$onUserLeave = function ($who) {

    $bot  = ActionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }
    unset($bot->users[$who]);


    if (DataAPI::isSet($who . '_joined')) {
        DataAPI::unSet($who . '_joined');
    }
    DataAPI::set($who . '_left', time());

    foreach ($bot->users as $id => $object) {
        if (DataAPI::isSet($id . '_left')) {
            if (DataAPI::get($who . '_left') + 300 < time()) {
                DataAPI::unSet($who . '_left');
                if (DataAPI::isSet($who . '_active')) {
                    DataAPI::unSet($who . '_active');
                }
            }
        }
    }

    return;
};
