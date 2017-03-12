<?php

$users = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    $ucount = count($bot->users);

    if ($ucount <= 0) {
        $bot->network->sendMessageAutoDetection($who, 'Why is there nobody here?', $type);
    } else {
        $bot->network->sendMessageAutoDetection($who, 'There is ' . $ucount . ' user' . ($ucount > 1 ? 's' : '') . ' online in this chatroom', $type);
    }
};