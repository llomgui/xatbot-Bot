<?php

$wikipedia = function (int $who, array $message, int $type) {
    
    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'wikipedia')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    unset($message[0]);
    $message = implode(' ', $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !wikipedia [search]', $type);
    }
    $stream = stream_context_create(['http'=> ['timeout' => 1]]);
    $page = file_get_contents(
        'http://en.wikipedia.org/w/api.php?action=opensearch&search=' . urlencode($message) . '&format=json&limit=1',
        false,
        $stream
    );
    if (!$page) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.wikipedia.cantsearch'),
            $type
        );
    }

    $json = json_decode($page);
    if (!empty($json[1])) {
        $wiki = 'Wikipedia page: http://en.wikipedia.org/wiki/' . $json[1][0];
    } else {
        $wiki = $bot->botlang('cmd.wikipedia.nothingfound');
    }
    $bot->network->sendMessageAutoDetection($who, $wiki, $type);
};
