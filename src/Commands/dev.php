<?php

use xatbot\IPC;
use xatbot\Logger;
use xatbot\Extensions;
use xatbot\API\DataAPI;
use xatbot\Models\Server;
use xatbot\Bot\XatVariables;

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

        case 'reload_servers':
            $servers = Server::all()->toArray();
            for ($i = 0; $i < sizeof($servers); $i++) {
                if (IPC::init() !== false) {
                    if (IPC::connect(strtolower($servers[$i] . '.sock')) !== false) {
                        IPC::write(sprintf("%s", 'reload'));
                        IPC::close();
                    } else {
                        $bot->network->sendMessageAutoDetection(
                            $who,
                            'Cannot connect to server ' . $servers[$i],
                            $type
                        );
                } else {
                    $bot->network->sendMessageAutoDetection(
                        $who,
                        'Cannot init connection for server ' . $servers[$i],
                        $type
                    );
                }
            }
            break;

        default:
            break;
    }
};
