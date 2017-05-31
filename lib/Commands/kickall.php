<?php

$kickall = function (int $who, array $message, int $type) {

    $bot = ActionAPI::getBot();

    if (!$bot->minrank($who, 'kickall')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->botHasPower(244)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['kickall']), $type);
    }

    switch (strtolower($message[1])) {   
        case 'all':
            $bot->network->socket->write('m', ['t' => '/ka', 'u' => xatVariables::getXatid()]);
            break;

        case 'register':
            $bot->network->socket->write('m', ['t' => '/kar', 'u' => xatVariables::getXatid()]);
            break;

        case 'toon':
            $bot->network->socket->write('m', ['t' => '/kap', 'u' => xatVariables::getXatid()]);
            break;

        case 'banned':
            $bot->network->socket->write('m', ['t' => '/kab', 'u' => xatVariables::getXatid()]);
            break;

        case 'raid':
            $bot->network->socket->write('m', ['t' => '/p', 'u' => xatVariables::getXatid()]);
            $bot->network->socket->write('m', ['t' => '/kap', 'u' => xatVariables::getXatid()]);
            break;

        default:
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !kickall [all/register/toon/banned/raid]', $type, true);
    }

};
