<?php

$twitch = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !twitch [username]', $type);
    }
    $message[1] = preg_replace("/[^a-zA-Z0-9_]/", "", $message[1]);

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Invalid twitch username', $type);
    }

    $stream = stream_context_create(['http'=> ['timeout' => 1]]);
    $page = file_get_contents('https://api.twitch.tv/kraken/streams/' . $message[1], false, $stream);

    /*
        API sends 404 if user doesnt exist :/
        if (!$page) {
            return $bot->network->sendMessageAutoDetection($who, 'Twitch API is not accessable at this monent or unknown username, please try again later.', $type);
        }
    */
    $twitch = json_decode($page);

    if (isset($twitch->error)) {
        return $bot->network->sendMessageAutoDetection($who, $twitch->message, $type, true);
    } elseif (!$page) {
        return $bot->network->sendMessageAutoDetection($who, 'Channel \'' . $message[1] . '\' does not exist.', $type);
    } elseif ($twitch->stream == null) {
        return $bot->network->sendMessageAutoDetection($who, '[' . ucfirst(strtolower($message[1])) . '] is not streaming right now. [Channel : https://twitch.tv/' . $message[1] . ' ]', $type);
    }
    $twitchA = [
        'Twitch user [' . $twitch->stream->channel->display_name . '] is currently streaming "' . $twitch->stream->game . '" with ' . $twitch->stream->viewers . ' viewers.',
        'Title: ' . $twitch->stream->channel->status,
        'Followers: ' . $twitch->stream->channel->followers,
        'Total views: ' . $twitch->stream->channel->views,
        'Partnered: ' . ($twitch->stream->channel->partner == true ? "Yes":"No"),
        $twitch->stream->channel->url
    ];
    $bot->network->sendMessageAutoDetection($who, implode(' | ', $twitchA), $type);
};
