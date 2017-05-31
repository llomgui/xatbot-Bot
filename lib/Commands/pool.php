<?php

$pool = function (int $who, array $message, int $type) {

    $bot = ActionAPI::getBot();

    if (!$bot->minrank($who, 'pool')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !pool [main/staff/banpool]', $type);
    }

    switch ($message[1]) {
        case 'main':
            $pool = 0;
            break;

        case 'staff':
            $pool = 1;
            break;

        case 'banpool':
            $pool = 2;
            break;        
        
        default:
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !pool [main/staff/banpool]', $type);
            break;
    }

    $bot->network->socket->write('w'.$pool);
};