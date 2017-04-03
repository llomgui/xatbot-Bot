<?php

$whatis = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'whatis')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
	
	if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !whatis [smiley]', $type, true);
	}
	
	$powers = xatVariables::getPowers();
	
	foreach($powers as $power){
		if($power['name'] == strtolower($message[1])){
			return $bot->network->sendMessageAutoDetection($who, ucfirst($power['name']) . ' is a power, not a smiley.', $type);
		}
		foreach($power['smilies'] as $smiley){
			if($smiley == strtolower($message[1])){
				return $bot->network->sendMessageAutoDetection($who, '('. $smiley . ') is from the power (' . $power['name'] . ')', $type);
			}
		}
	}
	
	if (in_array($message[1], xatVariables::getFreeSmilies())) {
		return $bot->network->sendMessageAutoDetection($who, '"' . $message[1] . '" is a free smiley.', $type);
	}
	
	$bot->network->sendMessageAutoDetection($who, '"' . $message[1] . '" was not found as a smiley.', $type);
};
