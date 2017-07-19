<?php

$countdown = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'countdown')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $releaseTime = OceanProject\Bot\XatVariables::getReleaseTime();

    if ($releaseTime > 1) {
        $message = 'The new power will be sold in ' . gmdate("H:i:s", $releaseTime - time()) . '.';
    } else {
        $message = 'There is no countdown at the moment.';
    }

    $bot->network->sendMessageAutoDetection($who, $message, $type);
};
