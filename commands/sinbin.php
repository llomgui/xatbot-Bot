<?php

$sinbin = function ($who, $message, $type) {

    $bot = actionAPI::getBot();
    
    if (!$bot->botHasPower(33)) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry i don\'t have \'sinbin\' power.', $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
        if ($type == 1) {
            $type = 2;
        }

        return $bot->network->sendMessageAutoDetection($who, 'Usage: !sinbin [regname/xatid] [hours]', $type);
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
        if (!$user->isMod()) {
            return $bot->network->sendMessageAutoDetection($who, 'That user is not a moderator.', $type);
        }
    
        $hours = $message[2];
        $bot->network->sendPrivateConversation($user->getID(), '/n' . $hours);
    } else {
        $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
    }
};
