<?php

$users = function (int $who, array $message, int $type) {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'users')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $ucount = count($bot->users);

    if ($ucount <= 0) {
        $bot->network->sendMessageAutoDetection($who, 'Why is there nobody here?', $type);
    } else {
        $bot->network->sendMessageAutoDetection($who, 'There is ' . $ucount . ' user' . ($ucount > 1 ? 's' : '') . ' online in this chatroom', $type);
    }
};
