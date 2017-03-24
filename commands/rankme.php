<?php

$rankme = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();
    if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !rankme [guest/member/mod/owner]', $type, true);
    }

    $user = $bot->users[$who];
    
    switch (strtolower($message[1])) {
        case 'guest':
            if (!$bot->minrank($who, 'guestme')) {
                return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
            }

            if (!$user->isGuest()) {
                $bot->network->changeRank($user->getID(), 'guest');
            }
            break;
            
        case 'member':
            if (!$bot->minrank($who, 'memberme')) {
                return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
            }

            if (!$user->isMember()) {
                $bot->network->changeRank($user->getID(), 'member');
            }
            break;
            
        case 'mod':
        case 'moderator':
            if (!$bot->minrank($who, 'modme')) {
                return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
            }

            if (!$user->isMod()) {
                $bot->network->changeRank($user->getID(), 'moderator');
            }
            break;
            
        case 'owner':
            if (!$bot->minrank($who, 'ownerme')) {
                return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
            }

            if (!$user->isOwner()) {
                $bot->network->changeRank($user->getID(), 'owner');
            }
            break;
    }
};
