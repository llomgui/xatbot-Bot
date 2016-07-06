<?php

$onChat = function ($array) {
    $chatInfo = explode(';=', $array['b']);
    /*
        0 = background
        1 = tabbed chat
        2 = tabbed chat id
        3 = language
        4 = radio
        5 = button color
    */
    if (isset($array['r'])) {
        switch (intval($array['r'])) {
            case 1: // Main
                break;
            case 2: // Mod
                break;
            case 3: // Member
                break;
            case 4: // Owner
                break;
            default: // Guest
                break;
        }
    }
    
    //Set Bot rank
};
