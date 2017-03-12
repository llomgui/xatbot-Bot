<?php

$onControlMessage = function (array $array) {
    
    $bot = actionAPI::getBot();

    if (isset($array['t']) && in_array($array['t'], ['/k', '/u']) && $array['d'] == $bot->network->logininfo['i']) {
        $bot->network->reconnect();
    }
};
