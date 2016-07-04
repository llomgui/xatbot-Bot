<?php

$zipban = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {

		if ($type == 1) {
			$type = 2;
		}

		return $bot->network->sendMessageAutoDetection($who, 'Usage: !zipban [ID/Regname] [hours] [reason]', $type);
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
		if($user->isBanned() == true) {
			$bot->network->sendMessageAutoDetection($who, 'That user is already zipbanned.', $type);
		} else {
			$hours   = $message[2];
			
			if (isset($message[3])) {

				unset($message[0]);
				unset($message[1]);
				unset($message[2]);

				$reason = implode(' ', $message);
			}

			$bot->network->ban($user->getID(), $hours, (!isset($reason) ? '' : $reason), 'g', 184);
		}
	} else {
		$bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
	}
};
