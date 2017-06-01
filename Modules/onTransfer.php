<?php

$onTransfer = function (int $from, string $type, string $message, int $to, int $xats, int $days) {

    if ($type != 'T') {
        return;
    }

    $bot = OceanProject\Bot\API\ActionAPI::getBot();

    if ($days > 0) {
        $xats = $days * 13 + $xats;
    }

    $premium = $bot->data->premium;
    $time = 0;

    if ($premium > time()) {
        $time = $premium - time();
    }

    $calc = ((0.12 * $xats) * 3600 * 24); // 250 xats for 30 days
    $time += $calc;
    $time += time();

    $bot->data->premium = $time;
    $bot->data->save();

    $bot->network->sendPrivateConversation(
        $from, 'Thanks for your payment, you added ' . $bot->sec2hms($calc) . ' to your bot.'
    );
    $bot->refresh();

    return;
};
