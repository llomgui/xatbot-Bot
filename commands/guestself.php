<?php

$guestself = function ($who, $message, $type) {
    
    $bot = actionAPI::getBot();
    $bot->network->sendMessage('/g');
};
