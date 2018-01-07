<?php

use OceanProject\Models\Bot;
use OceanProject\IPC;

$start = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if ($bot->data->chatid != '2594913') {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'You cannot use this command on this chat, please go to xat.com/xat164871174',
            $type
        );
    }

    if (empty($message[1]) || !is_numeric($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !start [botid]',
            $type
        );
    }

    $foo = Bot::find($message[1]);
    if ($foo == false) {
        return $bot->network->sendMessageAutoDetection($who, 'This botid does not exist!', $type);
    }

    $authorized = [];
    for ($i = 0; $i < sizeof($foo->users); $i++) {
        $authorized[] = $foo->users[$i]->xatid;
    }
    var_dump($authorized);

    if (!in_array($who, $authorized)) {
        return $bot->network->sendMessageAutoDetection($who, 'You are not able to start this bot!', $type);
    }

    $server = $foo->server->name;
    if (IPC::init() === false) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'The socket cannot be created, please contact an administrator!',
            $type
        );
    }

    if (IPC::connect(strtolower($server . '.sock')) === false) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'The socket is offline, please contact an administrator!',
            $type
        );
    }

    IPC::write(sprintf("%s %d", 'start', $message[1]));
    IPC::close();

    return $bot->network->sendMessageAutoDetection(
        $who,
        'Your bot is started!',
        $type
    );
};
