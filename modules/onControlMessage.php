<?php

$onControlMessage = function ($array) {
    
    $bot = actionAPI::getBot();

    if (isset($array['t']) && in_array($array['t'], ['/k', '/u'])) {
        $bot->network->reconnect();
    }
};
