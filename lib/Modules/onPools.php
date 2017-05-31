<?php

$onPools = function (array $array) {
    
    $bot = ActionAPI::getBot();

	$pools = explode(' ', $array['v']);
	array_shift($pools);

	$bot->chatInfo['pools'] = $pools;
};
