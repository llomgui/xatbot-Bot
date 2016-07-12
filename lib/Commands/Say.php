<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Say
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        unset($message[0]);
        $message = implode(' ', $message);

        if (empty($message)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'The message cannot be empty.',
                $type
            );
        } else {
            return $bot->network->sendMessageAutoDetection(
                $who,
                in_array($message[0], ['/', '#']) ? '_' . $message : $message,
                $type
            );
        }
    }
}
