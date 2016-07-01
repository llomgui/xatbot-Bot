<?php

$ban = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !ban [regname/xatid] [time] [reason]', $type);
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
		if (isset($message[3])) {

			unset($message[0]);
			unset($message[1]);

			$hours = $message[2];
			unset($message[2]);

			$reason = implode(' ', $message);
		} else {
			$reason = 'No reason';
		}

		$bot->network->ban($user->getID(), $hours, $reason);
	} else {
		$bot->network->sendMessageAutoDetection($who, 'User is not here', $type);
	}
};