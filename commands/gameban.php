<?php

$gameban = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2]) || !isset($message[3]) || empty($message[3]) || !is_numeric($message[3])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !gameban [ID/Regname] [snake/space/match/maze/code/slot] [hours] [reason]', $type);
	}

	if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
		$user = $bot->users[$message[1]];
	} else {
		foreach($bot->users as $id => $object) {
			if (is_object($object)) {
				if (strtolower($object->getRegname()) == strtolower($message[1])) {
					$user = $object;
					break;
				}
			}
		}
	}

	if (isset($user)) {
		$gameban = $message[2];
		$hours = $message[3];
		$reason = "";
		
		if (isset($message[4])) {

			unset($message[0]);
			unset($message[1]);
			unset($message[2]);
			unset($message[3]);

			$reason = implode(' ', $message);
		}
		
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
		}
		$bot->network->ban($user->getID(), $gameban, $hours, $reason);
	} else {
		$bot->network->sendMessageAutoDetection($who, 'User is not here', $type);
	}
};
