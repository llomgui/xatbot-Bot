<?php

$rankme = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();
    if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !rankme [guest/member/mod/owner]', $type, true);
    }

    $user = $bot->users[$who];
    
    switch (strtolower($message[1])) {
        case 'guest':
            if ($bot->flagToRank($who) < 1) {
                if (isset($bot->stafflist[$who])) {
                    if ($bot->stafflist[$who] >= 1) {
                        return true;
                    }
                }
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
            }

            if (!$user->isGuest()) {
                $bot->network->changeRank($user->getID(), 'guest');
            }
            break;
            
        case 'member':
            if ($bot->flagToRank($who) < 2) {
                if (isset($bot->stafflist[$who])) {
                    if ($bot->stafflist[$who] >= 2) {
                        return true;
                    }
                }
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
            }

            if (!$user->isMember()) {
                $bot->network->changeRank($user->getID(), 'member');
            }
            break;
            
        case 'mod':
        case 'moderator':
            if ($bot->flagToRank($who) < 3) {
                if (isset($bot->stafflist[$who])) {
                    if ($bot->stafflist[$who] >= 3) {
                        return true;
                    }
                }
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
            }

            if (!$user->isMod()) {
                $bot->network->changeRank($user->getID(), 'moderator');
            }
            break;
            
        case 'owner':
            if ($bot->flagToRank($who) < 4) {
                if (isset($bot->stafflist[$who])) {
                    if ($bot->stafflist[$who] >= 4) {
                        return true;
                    }
                }
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
            }

            if (!$user->isOwner()) {
                $bot->network->changeRank($user->getID(), 'owner');
            }
            break;
    }
};
