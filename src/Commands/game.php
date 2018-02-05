<?php

use xatbot\Bot\XatVariables;
use xatbot\API\DataAPI;

$game = function (int $who, array $message, int $type) {

    $bot  = xatbot\API\ActionAPI::getBot();

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

    $list = [
        'doodlerace' => 60189,
        'matchrace' => 60193,
        'snakerace' => 60195,
        'spacewar' => 60201,
        'hearts' => 60225,
        'switch' => 60239,
        'darts' => 60247,
        'zwhack' => 60257
    ];

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !game [' . implode(', ', array_keys($list)) . ', bot, start, times, prize, stop, bye]',
            $type
        );
    }
    switch (strtolower($message[1])) {
        case 'doodlerace':
        case 'matchrace':
        case 'snakerace':
        case 'spacewar':
        case 'hearts':
        case 'switch':
        case 'darts':
        case 'zwhack':
            $bot->network->socket->write('x', [
                'i' => $list[strtolower($message[1])],
                'u' => XatVariables::getXatid(),
                't' => 'j'
            ]);
            break;

        case 'bot':
            DataAPI::set('bot', true);
            $bot->network->sendMessage('!bot ' . (!empty($message[2]) ? $message[2] : ''));
            break;

        case 'stop':
        case 'start':
            $bot->network->sendMessage('!' . strtolower($message[1]));
            break;

        case 'bye':
        case 'exit':
            DataAPI::set('bot', false);
            $bot->network->sendMessage('!exit');
            break;

        case 'times':
        case 'prize':
            $message =  '!' . strtolower($message[1]) . ' ' . implode(' ', array_slice($message, 2));
            $bot->network->sendMessage($message);
            break;

        default:
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !game [' .  implode(', ', array_keys($list)) . ', bot, start, times, prize, stop, bye]',
                $type
            );
            break;
    }
};
