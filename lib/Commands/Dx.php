<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Dx
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1])) {
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !dx [days]', $type, true);
        }

        $days = round($message[1]);
        $xats = round($message[1] * 13);

        $bot->network->sendMessageAutoDetection(
            $who,
            $days . ' ' . ($days > 1 ? 'days' : 'day') . ' equals ' . $xats . ' xats',
            $type
        );
    }
}
