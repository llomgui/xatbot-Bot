<?php

$xatwiki = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

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
        return $bot->network->sendMessageAutoDetection($who, 'Wiki page was not found!', $type);
    }

    $bot->network->sendMessageAutoDetection(
        $who,
        'Wiki page for ' . $message[1] . ' : https://xat.wiki/' . $message[1],
        $type
    );
};
