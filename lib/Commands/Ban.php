<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Ban
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!isset($message[1]) ||
            empty($message[1]) ||
            !isset($message[2]) ||
            empty($message[2]) ||
            !is_numeric($message[2])
        ) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !ban [regname/xatid] [time] [reason]',
                $type,
                true
            );
        }

        if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
            $user = $bot->users[$message[1]];
        } else {
            foreach ($bot->users as $id => $object) {
                if (is_object($object)) {
                    if (strtolower($object->getRegname()) == strtolower($message[1])) {
                        $user = $object;
                        break;
                    }
                }
            }
        }

        if (isset($user)) {
            if ($user->isBanned()) {
                return $bot->network->sendMessageAutoDetection($who, 'That user is already banned.', $type);
            }

            $hours = $message[2];

            if (isset($message[3])) {
                $reason = implode(' ', array_slice($message, 3));
            }

            $bot->network->ban($user->getID(), $hours, $reason ?? '');
        } else {
            $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
        }
    }
}
