<?php

$onUserLeave = function ($who) {
    
    $bot  = actionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }
    unset($bot->users[$who]);
    
    
    if (dataAPI::is_set($who . '_joined')) {
        dataAPI::un_set($who . '_joined');
    }
    dataAPI::set($who . '_left', time());
    
    foreach ($bot->users as $id => $object) {
        if (dataAPI::is_set($id . '_left')) {
            if (dataAPI::get($who . '_left') + 300 < time()) {
                dataAPI::un_set($who . '_left');
                if (dataAPI::is_set($who . '_active')) {
                    dataAPI::un_set($who . '_active');  
                }
            }
        } 
    }

    return;
};
