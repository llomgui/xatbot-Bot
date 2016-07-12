<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Dev
{
    public function __invoke($who, $message, $type)
    {
        if (!in_array($who, [1000000000, 45193538, 1464424826])) {
            return;
        }

        $bot = ActionAPI::getBot();

        switch ($message[1]) {
            case 'reload':
                // TODO
                // reloadExtensions();
                // $bot->network->sendMessageAutoDetection($who, 'Extensions reloaded!', $type);
                break;

            case 'memory':
                $memory = [
                    'Bits'      => round(memory_get_usage(true) * 8),
                    'Bytes'     => memory_get_usage(true),
                    'Kilobytes' => round(memory_get_usage(true) / 1024),
                    'Megabytes' => round(memory_get_usage(true) / 1024 / 1024)
                ];

                $temp = [];
                foreach ($memory as $key => $val) {
                    array_push($temp, $key . ': ' . $val);
                }

                $bot->network->sendMessageAutoDetection($who, implode(' | ', $temp), $type);
                break;

            default:
                break;
        }
    }
}
