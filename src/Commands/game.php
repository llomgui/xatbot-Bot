<?php

use OceanProject\Bot\XatVariables;
use OceanProject\API\DataAPI;

$game = function (int $who, array $message, int $type) {

    $bot  = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'game')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!$bot->isPremium) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'I need to be premium to execute this command. (cry2)',
            $type
        );
    }

    $gameList = 'doodlerace, matchrace, snakerace, spacewar, hearts, switch, darts, zwhack';

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !game [' . $gameList . ', bot, start, time, stop, bye]',
            $type
        );
    }

    switch (strtolower($message[1])) {
        case 'doodlerace':
            $bot->network->socket->write('x', [
                'i' => 60189,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'matchrace':
            $bot->network->socket->write('x', [
                'i' => 60193,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'snakerace':
            $bot->network->socket->write('x', [
                'i' => 60195,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'spacewar':
            $bot->network->socket->write('x', [
                'i' => 60201,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'hearts':
            $bot->network->socket->write('x', [
                'i' => 60225,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'switch':
            $bot->network->socket->write('x', [
                'i' => 60239,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'darts':
            $bot->network->socket->write('x', [
                'i' => 60247,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'zwhack':
            $bot->network->socket->write('x', [
                'i' => 60257,
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'bot':
            DataAPI::set('bot', true);
            $bot->network->sendMessage('!bot');
            break;

        case 'start':
            $bot->network->sendMessage('!start');
            break;

        case 'stop':
            $bot->network->sendMessage('!stop');
            break;

        case 'bye':
        case 'exit':
            DataAPI::set('bot', false);
            $bot->network->sendMessage('!exit');
            break;

        case 'times':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->network->sendMessage('!times ' . $message);
            break;

        case 'prize':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->network->sendMessage('!prize ' . $message);
            break;

        default:
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !game [' . $gameList . ', bot, start, time, stop, bye]',
                $type
            );
            break;
    }
};
