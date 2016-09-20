<?php

$onLogout = function ($array) {
    
    $bot = actionAPI::getBot();
    if (!isset($array['e'])) {
    	return;
    }

    switch ($array['e']) {
    	case 'E16':
    		$bot->network->join();
    		break;

    	case 'E43':
    		exit('Bot\'s pw is expired. Please relogin with pin.');
    		break;
    }
};
