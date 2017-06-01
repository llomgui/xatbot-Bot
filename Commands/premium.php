<?php

$premium = function (int $who, array $message, int $type) {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'premium')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if ($bot->isPremium) {
        $bot->network->sendMessageAutoDetection(
        	$who, 'I am premium for the next ' . $bot->sec2hms($bot->data->premium - time()) . '.', $type
        );
    } else {
        $bot->network->sendMessageAutoDetection($who, 'I am not premium!', $type);
    }
};
