<?php

$twitch = function ($who, $message, $type) {
	$bot = actionAPI::getBot();
	
	if (empty($message[1]) || !isset($message[1])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !twitch [username]', $type);
	}
	
	$page = file_get_contents('https://api.twitch.tv/kraken/streams/' . $message[1]);
	if (!$page) {
		return $bot->network->sendMessageAutoDetection($who, 'Twitch API is not accessable at this monent or unknown username, please try again later.', $type);
	}
	$twitch = json_decode($page);
	
	if (isset($twitch->error)) {
		return $bot->network->sendMessageAutoDetection($who, $twitch->message, $type, true);
	} else if ($twitch->stream == null) {
		return $bot->network->sendMessageAutoDetection($who, 'Twitch user [' . $message[1] . '] is not streaming.', $type);
	}
	$twitchA = [
		'Twitch user [' . $twitch->stream->channel->display_name . '] is currently streaming "' . $twitch->stream->game . '" with ' . $twitch->viewers . ' viewers.',
		'Title: ' . $twitch->stream->channel->status,
		'Followers: ' . $twitch->stream->channel->followers,
		'Total views: ' . $twitch->stream->channel->views,
		'Partnered: ' . ($twitch->stream->channel->partner == true ? "Yes":"No"),
		'http://twitch.tv/' . $twitch->stream->channel->display_name
	];
	$bot->network->sendMessageAutoDetection($who, implode(' | ', $twitchA), $type);
};
