<?php

$test = function ($who, $message, $type) {
    $bot = actionAPI::getBot();

    unset($message[0]);
    
    $message = implode(' ', $message);
    
	if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type, true);
	}
	//remove parentheses, replace all #'s & alt255 with a space
    //not using regex because people like to use emojis/symbols with num
	$message = trim(str_replace(array('(',')','#',' '/*<-alt255*/), ' ', $message));	
	$message = explode(' ', $message);
	$message = array_filter($message, 'strlen');
	
	if (count($message) < 1) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type, true);
    }
    
	$bot->network->sendMessageAutoDetection($who, 'Test: (' . implode('#', $message) . '#)', $type);
};
