<?php

use Ocean\Xat\API\ActionAPI;
use Ocean\Xat\API\DataAPI;

$onUserLeave = function ($who) {

    $bot  = ActionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }
    unset($bot->users[$who]);


    if (DataAPI::isSetVariable($who . '_joined')) {
        DataAPI::unSetVariable($who . '_joined');
    }
    DataAPI::set($who . '_left', time());

    foreach ($bot->users as $id => $object) {
        if (DataAPI::isSetVariable($id . '_left')) {
            if (DataAPI::get($who . '_left') + 300 < time()) {
                DataAPI::unSetVariable($who . '_left');
                if (DataAPI::isSetVariable($who . '_active')) {
                    DataAPI::unSetVariable($who . '_active');
                }
            }
        }
    }

    return;
};
