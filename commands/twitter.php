<?php

$twitter = function ($who, $message, $type) {

	$link = 'http://107.170.196.241/twitter/'; 
	
	/**
		TO HOST IT YOURSELF: DOWNLOAD FILES AT http://skyleter.me/twitter.zip
		UPLOAD TO YOUR WEB HOSTING, AND CHANGE THE API KEYS IN CONFIG.PHP 
		(YOU NEED TO CREATE A TWITTER APP - apps.twitter.com).
		CHANGE THE LINK VARIABLE TO YOUR PATH. (url).
	**/

    $bot = actionAPI::getBot();

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !twitter [username]', $type);
    }
	
	

    $username = $message[1];
	
	/**
		REMEMBER TO HAVE PHP7 AND CURL INSTALLED!!! 
		IF NOT, THEN JUST INSTALL IT.
		ON UBUNTU: apt-get install php-curl
	**/
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, ''.$link.'?u='.$username.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$get = curl_exec($ch);
	// ^ You can use file_get_contents instead as this:
	// $get = file_get_contents(''.$link.'?u='.$username.'');
	// however you'll need fopen enabled.


	$bot->network->sendMessageAutoDetection($who, $get, $type);

};
