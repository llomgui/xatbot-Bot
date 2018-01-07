<?php

$xatwiki = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'xatwiki')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !xatwiki [search]', $type);
    }

    $response = file_get_contents(
        'https://util.xat.com/wiki/index.php?title=' . $message[1]
    );

    if (!$response) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.xatwiki.notfound'), $type);
    }

    $bot->network->sendMessageAutoDetection(
        $who,
        $bot->botlang('cmd.xatwiki.wikifor', [$message[1]]),
        $type
    );
};
