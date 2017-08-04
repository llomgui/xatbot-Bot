<?php

$onTransfer = function (int $from, string $type, string $message, int $to, int $xats, int $days) {

    if ($type != 'T') {
        return;
    }

    $bot = OceanProject\API\ActionAPI::getBot();

    $regname1 = '';
    $regname2 = '';

    if (isset($bot->users[$who])) {
        $regname1 = $bot->users[$who]->getRegname();
    } elseif ($who == 10101) {
        $regname1 = 'Ocean';
    }

    if (isset($bot->users[$to])) {
        $regname2 = $bot->users[$to]->getRegname();
    } elseif ($to == 10101) {
        $regname2 = 'Ocean';
    }

    $log = new Log;
    $log->chatid = $bot->data->chatid;
    $log->chatname = $bot->data->chatname;
    $log->typemessage = 6;
    $log->message = ((!is_null($regname1)) ? $regname1 . ' (' . $who . ')' : $who) . ' sent ' . $xats .
        ' xat(s) and ' . $days . ' day(s) to ' . ((!is_null($regname2)) ? $regname2 . ' (' . $to . ')' : $to);
    $log->save();

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
        $from,
        'Thanks for your payment, you added ' . $bot->sec2hms($calc) . ' to your bot.'
    );
    $bot->refresh();

    return;
};
