<?php

$kick = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    if (empty($message[1]) || !isset($message[1])) {
        if ($type == 1) {
            $type = 2;
        }

        return $bot->network->sendMessageAutoDetection($who, 'Usage: !kick [regname/xatid] [reason]', $type);
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

        $bot->network->kick($user->getID(), (!isset($reason) ? 'No reason' : $reason));
    } else {
        $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
    }

};
