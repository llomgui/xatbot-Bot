<?php

$say = function (int $who, array $message, int $type) {
    
    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'say')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    unset($message[0]);
    $message = implode(' ', $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('message.cannotbeempty'), $type);
    } else {
        //$message = str_replace('/', '_/', $message);
        if ($type != 3) {
            return $bot->network->sendMessageAutoDetection($who, '_' . $message, $type);
        } else {
            if ($bot->flagToRank($who) < $bot->stringToRank('mod')) {
                return $bot->network->sendMessage('[' . $who . '] ' . $message);
            } else {
                return $bot->network->sendMessage('_' . $message);
            }
        }
    }
};
