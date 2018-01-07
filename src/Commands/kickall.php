<?php

use xatbot\Bot\XatVariables;

$kickall = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'kickall')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->botHasPower(244)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['kickall']), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !kickall [all/register/toon/banned/raid]',
            $type,
            true
        );
    }

    switch (strtolower($message[1])) {
        case 'all':
            $bot->network->socket->write('m', ['t' => '/ka', 'u' => XatVariables::getXatid()]);
            break;

        case 'register':
            $bot->network->socket->write('m', ['t' => '/kar', 'u' => XatVariables::getXatid()]);
            break;

        case 'toon':
            $bot->network->socket->write('m', ['t' => '/kap', 'u' => XatVariables::getXatid()]);
            break;

        case 'banned':
            $bot->network->socket->write('m', ['t' => '/kab', 'u' => XatVariables::getXatid()]);
            break;

        case 'raid':
            $bot->network->socket->write('m', ['t' => '/p', 'u' => XatVariables::getXatid()]);
            $bot->network->socket->write('m', ['t' => '/kap', 'u' => XatVariables::getXatid()]);
            break;

        default:
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !kickall [all/register/toon/banned/raid]',
                $type,
                true
            );
    }
};
