<?php

$guestself = function ($who, $message, $type) {
    
    $bot = actionAPI::getBot();
    
    if (!$bot->botHasPower(32)) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry i don\'t have \'guestself\' power.', $type);
    }
    
    $bot->network->sendMessage('/g');
};
