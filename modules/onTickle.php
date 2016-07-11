<?php

$onTickle = function ($who, $array) {

    $bot = actionAPI::getBot();

    if (!isset($array['t'])) {
        return;
    }

    switch (substr($array['t'], 0, 2)) {
        case '/l':
            $key = 'tickle_' . $array['u'];
            if (!dataAPI::is_set($key)) {
                dataAPI::set($key, 0);
            }

            // Answer to tickle every 5 seconds
            if (time() - dataAPI::get($key) >= 5) {
                dataAPI::set($key, time());
                $bot->network->answerTickle($who);
            } else {
                return;
            }

            break;

        case '/a':
            if (!isset($bot->users[$who])) {
                return;
            }

            if (isset($array['po'])) {
                $bot->users[$who]->setDoubles($array['po']);
            }

            if (isset($array['x'])) {
                $bot->users[$who]->setXats($array['x']);
            }

            if (isset($array['y'])) {
                $bot->users[$who]->setDays($array['y']);
            }

            $bot->users[$who]->setPowers($array);

            break;
    }

    return;
};
