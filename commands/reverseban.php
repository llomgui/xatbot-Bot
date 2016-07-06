<?php

$reverseban = function ($who, $message, $type) {

    $bot = actionAPI::getBot();
    
    if (!$bot->botHasPower(176)) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry, but i don\'t have the power \'reverse\'.', $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
        if ($type == 1) {
            $type = 2;
        }

        return $bot->network->sendMessageAutoDetection($who, 'Usage: !reverseban [ID/Regname] [hours] [reason]', $type);
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
        if ($user->isReverseBanned()) {
            return $bot->network->sendMessageAutoDetection($who, 'That user is already reverse banned.', $type);
        }

        $hours   = $message[2];
        
        if (isset($message[3])) {
            $reason = implode(' ', array_slice($message, 3));
        }

        $bot->network->ban($user->getID(), $hours, (!isset($reason) ? '' : $reason), 'g', 176);
    } else {
        $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
    }
};
