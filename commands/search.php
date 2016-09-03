<?php

$search = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	if (!isset($message[1]) || empty($message[1])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !search [word]', $type);
	}

	$r_regname = '\[([^\]]+)\]</font>';
	$r_message = '>([^<]+)</a></b>';
	$r_link    = '>(xat.com/[^<]+)<';
	$regex     = '!' . $r_regname . '.+' . $r_message . '.+' . $r_link . '!Us';

	$a = [];
	$a['http']['method']  = 'POST';
	$a['http']['header']  = 'Content-Type: application/x-www-form-urlencoded';
	$a['http']['content'] = 'search='.$message[1];

	$fgc = file_get_contents('http://xat.com/web_gear/chat/search.php', false, stream_context_create($a));
	preg_match_all($regex, $fgc, $matches);

	unset($matches[0]);
	$array = [];

	foreach ($matches as $match) {
		foreach ($match as $key => $val) {
			$array[$key][] = $val;
		}
	}

	if (sizeof($array) >= 3) {
		for ($i = 0; $i < 3; $i++) {
			$bot->network->sendMessageAutoDetection($who, '['.$array[$i][0] . '] - ' . $array[$i][1]. ' at ' . $array[$i][2], $type);
			usleep(500000);
		}
	} else if (sizeof($array) > 0) {
		for ($i = 0; $i < sizeof($array); $i++) {
			$bot->network->sendMessageAutoDetection($who, '['.$array[$i][0] . '] - ' . $array[$i][1] . ' at ' . $array[$i][2], $type);
			usleep(50000);
		}
	} else
		$bot->network->sendMessageAutoDetection($who, 'Sorry, I don\'t have any message about this.', $type);
};