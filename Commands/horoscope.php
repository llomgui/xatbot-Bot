<?php

$horoscope = function (int $who, array $message, int $type) {
    
    $bot = OceanProject\Bot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'horoscope')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $date = (int)gmdate('j');

    $xor = 0;
    for ($i = 0; $i < $date; $i++) {
        $xor = ((($xor << 5) ^ $i) & 0xFF) ^ ($xor >> (32 - 5));
    }

    for ($i = 0; $i < 4; $i++) {
        $xor ^= $who >> ($i * 8) & 0xFF;
    }

    $love   = round(((($who >> 0x00) & 0xFF) ^ $xor) / 0xFF * 100);
    $health = round(((($who >> 0x08) & 0xFF) ^ $xor) / 0xFF * 100);
    $luck   = round(((($who >> 0x10) & 0xFF) ^ $xor) / 0xFF * 100);
    $money  = round(((($who >> 0x18) & 0xFF) ^ $xor) / 0xFF * 100);

    return $bot->network->sendMessageAutoDetection($who, 'Love: ' . $love . '%, health: ' . $health . '%, luck: ' . $luck . '%, money: ' . $money . '%.', $type);
};
