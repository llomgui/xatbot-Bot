<?php

$onIdle = function (array $array) {
    $bot = xatbot\API\ActionAPI::getBot();

    // tempfix to avoid idle (j2 packet has too many attributes to connect)
    if (isset($array['e']) && $array['e'] == 'I04') {
        $bot->data->chatpw = null;
        $bot->data->save();
        $bot->refresh();
    } else {
        $bot->network->reconnect();
    }
};
