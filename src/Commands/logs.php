<?php

$logs = function (int $who, array $message, int $type) {

    $bot  = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'logs')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !is_numeric($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !logs [amount]',
            $type
        );
    }

    $amount = (int) $message[1];
    $logsLink = 'https://oceanproject.fr/panel/bot/logs/' . $bot->data->id . '/' . $amount;

    return $bot->network->sendMessageAutoDetection(
        $who,
        'Logs can be seen here: ' . $logsLink,
        $type
    );
};
