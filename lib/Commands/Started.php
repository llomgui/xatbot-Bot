<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Started
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        $started = time() - $bot->started;

        $bot->network->sendMessageAutoDetection(
            $who,
            'I was started ' . $bot->secondsToTime($started) . ' ago.',
            $type
        );
    }
}
