<?php

$onPools = function (array $array) {
    
    $bot = xatbot\API\ActionAPI::getBot();

    $pools = explode(' ', $array['v']);
    array_shift($pools);

    $bot->chatInfo['pools'] = $pools;
};
