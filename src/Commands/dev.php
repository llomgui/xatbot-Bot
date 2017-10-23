<?php

use OceanProject\Extensions;
use OceanProject\Bot\XatVariables;
use OceanProject\API\DataAPI;

$dev = function (int $who, array $message, int $type) {

    if (!in_array($who, XatVariables::getDevelopers())) {
        return;
    }

    $bot = OceanProject\API\ActionAPI::getBot();

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
            $data = DataAPI::dumpVars();
            print_r($data);
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
