<?php

use xatbot\Extensions;
use xatbot\Bot\XatVariables;
use xatbot\API\DataAPI;
use xatbot\Logger;

$dev = function (int $who, array $message, int $type) {

    if (!in_array($who, XatVariables::getDevelopers())) {
        return;
    }

    $bot = xatbot\API\ActionAPI::getBot();

    switch ($message[1]) {
        case 'reload':
            Extensions::readExtensions();
            $bot->network->sendMessageAutoDetection($who, 'Extensions reloaded!', $type);
            break;

        case 'update':
            XatVariables::update();
            $bot->network->sendMessageAutoDetection($who, 'Config updated!', $type);
            break;

        case 'test':
            for ($i=0; $i < 500; $i++) {
                Logger::getLogger()->debug($i);
                $bot->network->tempRank(220711, 'moderator', 6);
            }
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
