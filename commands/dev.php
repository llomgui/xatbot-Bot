<?php

$dev = function (int $who, array $message, int $type) {

    if (!in_array($who, xatVariables::getDevelopers())) {
        return;
    }

    $bot = actionAPI::getBot();

    switch ($message[1]) {

        case 'reload':
            reloadExtensions();
            $bot->network->sendMessageAutoDetection($who, 'Extensions reloaded!', $type);
            break;
 
        case 'memory':
            $usage = memory_get_usage(true);
            $memory = [
                'Bits: ' . round($usage * 8),
                'Bytes: ' . $usage,
                'Kilobytes: ' . round($usage / 1024),
                'Megabytes: ' . round($usage / 1024 / 1024)
            ];
            
            $bot->network->sendMessageAutoDetection($who, implode(' | ', $memory), $type);
            break;

        default:
            break;
    }

};
