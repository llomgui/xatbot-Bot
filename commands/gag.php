<?php

$gag = function ($who, $message, $type) {

    $bot = actionAPI::getBot();
    
    if (!$bot->botHasPower(41)) {
        return $bot->network->sendMessageAutoDetection($who, sprintf('Sorry, but i don\'t have the power \'%s\'.', 'gag'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        if ($type == 1) {
            $type = 2;
        }

        return $bot->network->sendMessageAutoDetection($who, 'Usage: !gag [regname/xatid] [reason]', $type);
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
        if ($user->isGagged()) {
            return $bot->network->sendMessageAutoDetection($who, 'That user is already gagged.', $type);
        }

        if (isset($message[2])) {
            $reason = implode(' ', array_slice($message, 2));
        }
        $bot->network->ban($user->getID(), 1, (!isset($reason) ? '' : $reason), 'gg');
    } else {
        $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
    }
};
