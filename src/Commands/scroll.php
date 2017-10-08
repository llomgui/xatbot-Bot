<?php

$scroll = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'scroll')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (in_array(strtolower($bot->chatInfo['rank']), ['guest', 'member', 'moderator'])) {
        return $bot->network->sendMessageAutoDetection($who, 'I need to be owner+ to set a scroller. :(', $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !scroll [message/clear]', $type);
    }

    switch (strtolower($message[1])) {
        case 'clear':
            $bot->network->sendMessage('/s');
            $bot->network->sendMessageAutoDetection($who, 'The scroll is now cleared!', $type);
            break;
        
        default:
            unset($message[0]);
            $bot->network->sendMessage('/s' . implode(' ', $message));
            $bot->network->sendMessageAutoDetection($who, 'The scroll is now set to ' . implode(' ', $message), $type);
            break;
    }
};
