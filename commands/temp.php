<?php

$temp = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'temp')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1]) || empty($message[2])       ||
        empty($message[3]) || !is_numeric($message[3]) ||
        $message[3] < 0    || $message[3] > 24) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !temp [mem/mod/own] [xatid/regname] [time(0-24)]', $type, true);
    }

    if (is_numeric($message[2]) && isset($bot->users[$message[2]])) {
        $user = $bot->users[$message[2]];
    } else {
        foreach ($bot->users as $id => $object) {
            if (is_object($object) && strtolower($object->getRegname()) == strtolower($message[2])) {
                $user = $object;
                break;
            }
        }
    }

    if (isset($user)) {
        $message[1] = strtolower($message[1]);
        switch ($message[1]) {
            case 'mem':
            case 'member':
            case 'membre':
                if (!$bot->botHasPower(61)) {
                    return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['tempmem']), $type);
                }
                $bot->network->sendPrivateConversation($user->getID(), '/mb' . $message[3]);
                break;

            case 'mod':
            case 'moderator':
            case 'moderateur':
                if (!$bot->botHasPower(11)) {
                    return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['tempmod']), $type);
                }
                $bot->network->sendPrivateConversation($user->getID(), '/m' . $message[3]);
                break;

            case 'own':
            case 'owner':
                if (!$bot->botHasPower(79)) {
                    return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['tempown']), $type);
                }
                $bot->network->sendPrivateConversation($user->getID(), '/mo' . $message[3]);
                break;
            default:
                break;
        }
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type);
    }
};
