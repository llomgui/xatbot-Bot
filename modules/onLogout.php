<?php

$onLogout = function ($array) {
    
    $bot = actionAPI::getBot();
    if (isset($array['e']) && $array['e'] == "E16") {// chat reset
        $bot->network->join();
    }
};
