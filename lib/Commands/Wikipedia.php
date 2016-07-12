<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Wikipedia
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        unset($message[0]);
        $message = implode(' ', $message);

        if (empty($message)) {
            return $bot->network->sendMessageAutoDetection($who, 'You didn\'t give me anything to search.', $type);
        }
        $stream = stream_context_create(['http'=> ['timeout' => 1]]);
        $page = file_get_contents(
            'http://en.wikipedia.org/w/api.php?action=opensearch&search='
            . urlencode($message)
            . '&format=json&limit=1',
            false,
            $stream
        );

        if (!$page) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'I can\'t reach wikipedia.org at this monent, please try again later.',
                $type
            );
        }

        $json = json_decode($page);
        if (!empty($json[1])) {
            $wiki = "Wikipedia page: http://en.wikipedia.org/wiki/" . $json[1][0];
        } else {
            $wiki = "Wikipedia page could not be found.";
        }
        $bot->network->sendMessageAutoDetection($who, $wiki, $type);
    }
}
