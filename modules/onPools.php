<?php

$onPools = function ($array) {
    
    $bot = actionAPI::getBot();

	$pools = explode(' ', $array['v']);
	array_shift($pools);

	$bot->chatInfo['pools'] = $pools;
};
