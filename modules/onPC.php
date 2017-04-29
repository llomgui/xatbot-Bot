<?php

$onPC = function (int $who, string $message) {

    $bot = actionAPI::getBot();
    $message = explode(' ', $message);

    if (!isset($bot->users[$who])) {
        return;
    }

    if (!isset($message[0])) {
        return;
    }

    if ($bot->data->automember == 'maths' && dataAPI::is_set('automember_' . $who)) {
        if (dataAPI::get('automember_' . $who) == $message[0]) {
            $bot->network->sendPrivateConversation($who, 'You are now a member');
            dataAPI::un_set('automember_' . $who);
            $bot->network->ChangeRank($who, 'member');
        } else {
            $bot->network->sendPrivateConversation($who, 'Wrong answer!');
        }
    }

    return;
};
