<?php

$youtube = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'youtube')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
	
	if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !youtube [search]', $type, true);
	}

	$key = xatVariables::getAPIKeys()['youtube'];

    if (empty($key)) {
        return $bot->network->sendMessageAutoDetection($who, "Youtube API Key needs to be setup", $type);
    }

	$response = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&q='.urlencode($message[1]).'&key='. $key . '&type=video&maxResults=3'), true);
    
    if ($response['error']) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry i can\'t search youtube at this time, please try again later.', $type);
    }

	foreach ($response['items'] as $result) {
		$bot->network->sendMessageAutoDetection($who, $result['snippet']['title'].' - http://youtube.com/watch?v='.$result['id']['videoId'], $type);
		sleep(1);
	}
};
