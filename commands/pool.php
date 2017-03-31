<?php

$pool = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'pool')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
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