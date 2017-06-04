<?php

$clear = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'clear')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    for ($i = $bot->messageCount - 23; $i <= $bot->messageCount; $i++) {
        $bot->network->sendMessage('/d' . ($i + 1));
    }

    $bot->network->sendMessageAutoDetection($who, 'Chat is now cleared!', $type);
};
