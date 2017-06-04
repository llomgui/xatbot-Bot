<?php

use OceanProject\API\DataAPI;

$onPC = function (int $who, string $message) {

    $bot = OceanProject\API\ActionAPI::getBot();
    $message = explode(' ', $message);

    if (!isset($bot->users[$who])) {
        return;
    }

    if (!isset($message[0])) {
        return;
    }

    if ($bot->data->automember == 'maths' && DataAPI::isSetVariable('automember_' . $who)) {
        if (DataAPI::get('automember_' . $who) == $message[0]) {
            $bot->network->sendPrivateConversation($who, 'You are now a member');
            DataAPI::unSetVariable('automember_' . $who);
            $bot->network->ChangeRank($who, 'member');
        } else {
            $bot->network->sendPrivateConversation($who, 'Wrong answer!');
        }
    }

    return;
};
