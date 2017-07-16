<?php

$weather = function (int $who, array $message, int $type) {

    $bot  = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'weather')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !weather [search]', $type, true);
    }
 
    $key = OceanProject\Bot\XatVariables::getAPIKeys()['weather'];
 
    if (empty($key)) {
        return $bot->network->sendMessageAutoDetection($who, 'Weather API Key needs to be setup', $type);
    }

    unset($message[0]);
    $message = implode(' ', $message);
 
    $response = json_decode(
        file_get_contents('http://api.openweathermap.org/data/2.5/weather?APPID=' . $key . '&q=' .
        urlencode($message)),
        true
    );
   
    // api returns 404 if city not found
    if (!($response['cod'] == 200)) {
        return $bot->network->sendMessageAutoDetection($who, 'This city was not found or something went wrong!', $type);
    }

    $smilies = [
        'Rain'         => '(rainy)',
        'Clear'        => '(cleary)',
        'Clouds'       => '(clouds#ffffff)',
        'Mist'         => '(foggy)',
        'Thunderstorm' => '(stormy)'
    ];
 
    // save infos in an array
    $weatherData = [
        'weather' => [
            'cityname'    => $response['name'],
            'country'     => $response['sys']['country'],
            'description' => ucfirst($response['weather'][0]['description']),
            'smiley'      => $response['weather'][0]['main']
        ],
        'temp' => [
            'humidity'   => $response['main']['humidity'] ?? 'Unknown',
            'kelvin'     => round($response['main']['temp']),
            'celsius'    => round($response['main']['temp'] - 273.15),
            'fahrenheit' => round((($response['main']['temp'] - 273.15) * 1.8) + 32)
        ]
    ];
 
    $weatherInfos = $weatherData['weather']; // weather informations
    $weatherTemp  = $weatherData['temp']; // weather temp informations
 
    return $bot->network->sendMessageAutoDetection(
        $who,
        'Weather for ' . $weatherInfos['cityname'] . '(' . $weatherInfos['country'] . ') : ' .
        $weatherInfos['description'] . $smilies[$weatherInfos['smiley']] . ' | Temperature : [' .
        $weatherTemp['fahrenheit'] . '°F | ' . $weatherTemp['celsius'] . '°C | ' . $weatherTemp['kelvin'] .
        'K] | Humidity : ' . $weatherTemp['humidity'] . '%',
        $type
    );
};
