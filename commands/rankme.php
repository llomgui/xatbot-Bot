<?php

$rankme = function ($who, $message, $type) {

    if (!in_array($who, [1000000000, 45193538, 1464424826])) {
        return;
    }

    $bot = actionAPI::getBot();
	if (empty($message[1]) || !isset($message[1])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !rankme [guest/member/mod/owner]', $type, true);
    }
	
	switch(strtolower($message[1])){
		case 'guest':
			/*
			
			TODO Check rank
			
			*/
			$bot->network->sendPrivateConversation($who, '/r');
			break;
			
		case 'member':
			/*
			
			TODO Check rank
			
			*/
			$bot->network->sendPrivateConversation($who, '/e');
			break;
			
		case 'mod':
		case 'moderator':
			/*
			
			TODO Check rank
			
			*/
			$bot->network->sendPrivateConversation($who, '/m');
			break;
			
		case 'owner':
			/*
			
			TODO Check rank
			
			*/
			$bot->network->sendPrivateConversation($who, '/M');
			break;
	}
};
