<?php

$rankme = function (int $who, array $message, int $type) {

    if (!in_array($who, xatVariables::getDevelopers())) {
        return;
    }

    $bot = actionAPI::getBot();
	if (empty($message[1]) || !isset($message[1])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !rankme [guest/member/mod/owner]', $type, true);
    }

    $user = $bot->users[$who];
	
	switch(strtolower($message[1])){
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
			
		case 'mod':
		case 'moderator':
			if (!$user->isMod()) {
                $bot->network->changeRank($user->getID(), 'moderator');
            }
			break;
			
		case 'owner':
			if (!$user->isOwner()) {
                $bot->network->changeRank($user->getID(), 'owner');
            }
			break;
	}
};
