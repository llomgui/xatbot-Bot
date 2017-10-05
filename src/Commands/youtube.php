<?php

$youtube = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'youtube')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !youtube [search]', $type, true);
    }

    $key = OceanProject\Bot\XatVariables::getAPIKeys()['youtube'];

    if (empty($key)) {
        return $bot->network->sendMessageAutoDetection($who, "Youtube API Key needs to be setup", $type);
    }
    
    unset($message[0]);
    $message = implode(' ', $message);

    $response = json_decode(
        file_get_contents(
            'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' .
                urlencode($message) . '&key=' . $key . '&type=video&maxResults=3'
        ),
        true
    );
    
    if (isset($response['error'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Sorry i can\'t search youtube at this time, please try again later.',
            $type
        );
    }

    if ($response['pageInfo']['totalResults'] < 1) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'I found nothing for that search. :(',
            $type
        );
    }

    foreach ($response['items'] as $result) {
        $newMessage = $result['snippet']['title'].' - http://youtube.com/watch?v='.$result['id']['videoId'];

        if (sizeof($bot->packetsinqueue) > 0) {
            $bot->packetsinqueue[max(array_keys($bot->packetsinqueue)) + 1000] = [
                'who' => $who,
                'message' => $newMessage,
                'type' => $type
            ];
        } else {
            $bot->packetsinqueue[round(microtime(true) * 1000) + 1000] = [
                'who' => $who,
                'message' => $newMessage,
                'type' => $type
            ];
        }
    }
};
