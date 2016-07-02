<?php

$gamebanme = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !gamebanme [snake/space/match/maze/code/slot] [hours]', $type);
	}

	$gameban = $message[1];
	$hours = $message[2];
	
	switch(trim(strtolower($gameban))){
		case 'snake':
		case 'snakeban':
			$gameban = 2;
			break;
			
		case 'space':
		case 'spaceban':
			$gameban = 3;
			break;
			
		case 'match':
		case 'matchban':
			$gameban = 4;
			break;
			
		case 'maze':
		case 'mazeban':
			$gameban = 5;
			break;
			
		case 'code':
		case 'codeban':
			$gameban = 6;
			break;
			
		case 'slot':
		case 'slotban':
			$gameban = 7;
			break;
			
		default:
			return $bot->network->sendMessageAutoDetection($who, "That's not a valid gameban", $type);
			break;
	}
	$bot->network->ban($who, $gameban, $hours, "Requested");
};
