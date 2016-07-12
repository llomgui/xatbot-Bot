<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Choose
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        unset($message[0]);
        $message = implode(' ', $message);
        $message = preg_split('/ or /', strtolower($message), 2);

        if ((!isset($message[0]) || empty($message[0])) || (!isset($message[1]) || empty($message[1]))) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !choose [first choice] or [second choice]',
                $type,
                true
            );
        }

        $choice = rand(0, 100) > 50 ? $message[0] : $message[1];
        $bot->network->sendMessageAutoDetection($who, 'I have chosen \'' . $choice. '\'.', $type);
    }
}
