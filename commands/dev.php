<?php

$dev = function ($who, $message, $type) {

    if (!in_array($who, [1000000000, 45193538, 1464424826])) {
        return;
    }

    $bot = actionAPI::getBot();

    switch ($message[1]) {

        case 'reload':
            reloadExtensions();
            $bot->network->sendMessageAutoDetection($who, 'Extensions reloaded!', $type);
            break;

			
		case 'response':
				$time_start = microtime(true); 
				$ms = file_get_contents('http://loripsum.net/api/1/veryshort/plaintext');
				$msx = file_get_contents('http://loripsum.net/api/1/veryshort/plaintext');
				$bot->network->sendMessageAutoDetection($who, ''.$ms.'('.substr(md5(rand(1000, 9999999)), 0, 5).')' , $type);
				$bot->network->sendMessageAutoDetection($who, ''.$msx.'('.substr(md5(rand(1000, 9999999)), 0, 5).')' , $type);
				$bot->network->sendMessageAutoDetection($who, 'Total execution time in seconds: ' . (microtime(true) - $time_start).'', $type);
			break;
        case 'memory':
            $memory = [
                'Bits'      => round(memory_get_usage(true) * 8),
                'Bytes'     => memory_get_usage(true),
                'Kilobytes' => round(memory_get_usage(true) / 1024),
                'Megabytes' => round(memory_get_usage(true) / 1024 / 1024)
            ];

            $temp = [];
            foreach ($memory as $key => $val) {
                array_push($temp, $key . ': ' . $val);
            }

            $bot->network->sendMessageAutoDetection($who, implode(' | ', $temp), $type);
            break;

        default:
            break;
    }

};
