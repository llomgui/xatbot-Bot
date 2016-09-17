<?php

$dev = function ($who, $message, $type) {

    if (!in_array($who, [1000000000, 45193538])) {
        return;
    }

    $bot = actionAPI::getBot();

    switch ($message[1]) {

        case 'reload':
            reloadExtensions();
            $bot->network->sendMessageAutoDetection($who, 'Extensions reloaded!', $type);
            break;
 
        case 'memory':
            $memory = [
                'Bits: ' . round(memory_get_usage(true) * 8),
                'Bytes: ' . memory_get_usage(true),
                'Kilobytes: ' . round(memory_get_usage(true) / 1024),
                'Megabytes: ' . round(memory_get_usage(true) / 1024 / 1024)
            ];
            
            $bot->network->sendMessageAutoDetection($who, implode(' | ', $memory), $type);
            break;

        default:
            break;
    }

};
