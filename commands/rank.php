<?php

$rank = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'rank')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    if (empty($message[1]) || empty($message[2])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !rank [member/mod/owner] [ID/Regname]', $type, true);
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
        switch ($message[1]) {
            case 'guest':
                if (!$user->isGuest()) {
                    $bot->network->changeRank($user->getID(), 'guest');
                }
                break;

            case 'member':
                if (!$user->isMember()) {
                    $bot->network->changeRank($user->getID(), 'member');
                }
                break;

            case 'moderator':
            case 'mod':
                if (!$user->isMod()) {
                    $bot->network->changeRank($user->getID(), 'moderator');
                }
                break;

            case 'owner':
                if (!$user->isOwner()) {
                    $bot->network->changeRank($user->getID(), 'owner');
                }
                break;

            default:
                $bot->network->sendMessageAutoDetection($who, 'Usage: !rank [member/mod/owner] [ID/Regname].', $type);
                break;
        }
    } else {
        return $bot->network->sendMessageAutoDetection($who, 'This user is not in the chat.', $type);
    }

};