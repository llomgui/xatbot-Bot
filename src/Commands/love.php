<?php

$love = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'love')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $l        = 0;
    $lover[0] = null;
    $lover[1] = null;

    for ($j = 1; $j <= (sizeof($message) - 1); $j++) {
        if ($message[$j] == "&") {
            $l++;
        } else {
            $lover[$l] .= $message[$j] . ' ';
        }
    }

    $lover[0] = trim($lover[0]);
    $lover[1] = trim($lover[1]);

    if (!empty($lover[0]) and !empty($lover[1])) {
        $one = strtolower($lover[0]);
        $two = strtolower($lover[1]);
        
        $love = 16;

        for ($j = 0; $j < sizeof($lover); $j++) {
            for ($k = 0; $k < strlen($lover[$j]); $k++) {
                $love += ord(strtoupper($lover[$j][$k]));
            }
        }

        $love = $love % 102;
            
        $reg = $bot->users[$who]->getRegname();
        if (!empty($reg)) {
            $who = $reg;
        }
            
        if ($love == 101) {
            $bot->network->sendMessageAutoDetection(
                $who,
                ' ['.$who.'] - Woooow ! ' . $lover[0] . ' + ' . $lover[1] . ' = (L#) !',
                $type
            );
        } else {
            $bot->network->sendMessageAutoDetection(
                $who,
                ' ['.$who.'] - Love test: ' . $lover[0] . ' and ' . $lover[1] . ' are ' . $love . '% compatible.',
                $type
            );
        }
    } else {
        $bot->network->sendMessageAutoDetection($who, 'Usage: !love [lover1] & [lover2]', $type);
    }
};
