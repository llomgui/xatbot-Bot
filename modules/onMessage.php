<?php

$onMessage = function (int $who, string $message) {

	$bot = actionAPI::getBot();

	if ($bot->isPremium && $bot->data->premium < time()) {
		$bot->network->sendMessage('Ah! My premium time is over (cry2)');
		return $bot->refresh();
	}

	if (!empty($bot->responses)) {

		$message2 = explode(' ', $message);
		$replace = [
			'{name}'    => $bot->users[$who]->getNick(),
			'{status}'  => $bot->users[$who]->getStatus(), 
			'{regname}' => $bot->users[$who]->getRegname(),
			'{users}'   => sizeof($bot->users),
			'{cmdcode}' => $bot->data->customcommand,
			'{id}'      => $bot->users[$who]->getID(),			
		];

		$responses = $bot->responses;
		foreach ($responses as $key => $value) {
			if (strlen($key) == 0) {
				continue;
			}

			if ($key[0] == '*') {
				$key = substr($key, 1);

				if (strtolower($key) == strtolower($message)) {
					foreach ($replace as $k => $v) {
						$value = str_replace(strtolower($k), $v, $value);
					}
					
					return $bot->network->sendMessage($value);
				}
			} else {
				if (sizeof(explode(' ', $key)) > 1) {
					if (stripos($message, $key) !== false) {
						foreach ($replace as $k => $v) {
							$responses = str_replace(strtolower($k), $v, $responses);
						}

						return $bot->network->sendMessage($responses[$key]);
					}
				} else {
					for ($i = 0; $i < sizeof($message2); $i++) {
						if ($message2[$i] == $key) {
							foreach($replace as $k => $v) {
								$responses = str_replace(strtolower($k), $v, $responses);
							}

							return $bot->network->sendMessage($responses[$key]);
						}
					}
				}
			}
		}
	}

	return;

};
