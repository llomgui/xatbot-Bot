<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Badge
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!$bot->botHasPower(264)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                sprintf('Sorry, but i don\'t have the power \'%s\'.', 'badge'),
                $type
            );
        }

        if (!isset($message[1]) || empty($message[1])) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !badge [regname/xatid] [reason]',
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
            if ($user->isBadged()) {
                return $bot->network->sendMessageAutoDetection($who, 'That user is already badged.', $type);
            }

            if (isset($message[2])) {
                $reason = implode(' ', array_slice($message, 2));
            }

            $bot->network->sendPrivateConversation($user->getID(), '/nb' . ($reason ?? ''));
        } else {
            $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
        }
    }
}
