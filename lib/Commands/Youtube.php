<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Youtube
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (empty($message[1]) || !isset($message[1])) {
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !youtube [search]', $type, true);
        }

        $key = "AIzaSyBwBs83XSWHOlxIKrf7VvaE-AbIl_zqIbw"; // TODO Use your own API key (instead of mine)

        $response = json_decode(
            file_get_contents(
                'https://www.googleapis.com/youtube/v3/search?part=snippet&q='
                . urlencode($message[1])
                . '&key='
                . $key
                . '&type=video&maxResults=3'
            ),
            true
        );

        foreach ($response['items'] as $result) {
            $bot->network->sendMessageAutoDetection(
                $who,
                $result['snippet']['title'].' - http://youtube.com/watch?v='.$result['id']['videoId'],
                $type
            );
            sleep(1);
        }
    }
}
