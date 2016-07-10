<?php

$shortname = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !shortname [name]', $type);
    }

	$message[1] = trim($message[1]);

    $stream = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => 'GroupName=' . $message[1] . '&Quote=Get+cost&YourEmail=&agree=ON&l_dt=&l_k2=&password=',
            'timeout' => 1
        ]
    ];
    $res = file_get_contents('http://xat.com/web_gear/chat/BuyShortName.php', false, stream_context_create($stream));
	
    if (!$res) {
		return $bot->network->sendMessageAutoDetection($who, 'Sorry, i couldn\'t find a price for ' . $message[1], $type);
	}
	
	if (strpos($res, '**<span data-localize=buy.notallowed>Name is not allowed. Please try another.</span>**')) {
		return $bot->network->sendMessageAutoDetection($who, '_' . $message[1] . ' is not allowed. Please try another (shrug)', $type);
	} elseif (strpos($res, '**<span data-localize=buy.nametaken>Sorry, name already taken</span> (1).**')) {
		return $bot->network->sendMessageAutoDetection($who, '_' . $message[1].' is already taken. Please try another (shrug)', $type);
	} elseif (strpos($res, '**<span data-localize=buy.short>Name is too short</span>**')) {
		return $bot->network->sendMessageAutoDetection($who, '_' . $message[1].' is to short. Please try another (shrug)', $type);	
	} elseif (strpos($res, '**<span data-localize=buy.long>Name is too long</span>**')) {
		return $bot->network->sendMessageAutoDetection($who, '_' . $message[1].' is to long. Please try another (shrug)', $type);
	}
	
	$price = trim(preg_replace('!^.+Cost</span>:([0-9]*) xats.+$!Usi', '$1', $res));
	
	if (strlen($price) < 999) {
		$bot->network->sendMessageAutoDetection($who, '_' . $message[1] . ' cost ' . $price . ' xats.', $type);
	} else {
		$bot->network->sendMessageAutoDetection($who, 'Sorry, i couldn\'t find a price for ' . $message[1], $type);
	}
};