<?php

use OceanProject\API\DataAPI;

$radio = function (int $who, array $message, int $type) {

    $bot  = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'radio')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (DataAPI::isSetVariable('radio')) {
        $infos = DataAPI::get('radio');
        if ($infos['lastCheck'] > time()) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Listening to: ' . $infos['song'] . ' ' . $infos['listeners'] . '/' . $infos['max'] . '.',
                $type
            );
        }
    }

    $song = $bot->getCurrentSong();

    if ($song == false) {
        return $bot->network->sendMessageAutoDetection($who, 'You have an error with the radio!', $type);
    }

    DataAPI::set('radio', $song);

    return $bot->network->sendMessageAutoDetection(
        $who,
        'Listening to: ' . $song['song'] . ' ' . $song['listeners'] . '/' . $song['max'] . '.',
        $type
    );
};
