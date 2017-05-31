<?php

$onLogout = function (array $array) {
    
    $bot = ActionAPI::getBot();
    if (!isset($array['e'])) {
        return;
    }

    switch ($array['e']) {
        case 'E03': // No answer after handshake from xat server
            $bot->network->join();
            break;

        case 'E16': // Chat reset
            $bot->network->join();
            break;

        case 'E43': // Some args are missing in j2
            $bot->network->join();
            break;
    }
};