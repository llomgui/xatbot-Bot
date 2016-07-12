<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Kick
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (empty($message[1]) || !isset($message[1])) {
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !kick [regname/xatid] [reason]', $type, true);
        }

        if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
            $user = $bot->users[$message[1]];
        } else {
            foreach ($bot->users as $id => $object) {
                if (is_object($object) && strtolower($object->getRegname()) == strtolower($message[1])) {
                    $user = $object;
                    break;
                }
            }
        }

        if (isset($user)) {
            if (isset($message[2])) {
                $reason = implode(' ', array_slice($message, 2));
            }

            $bot->network->kick($user->getID(), $reason ?? 'No reason');
        } else {
            $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
        }
    }
}
