<?php

namespace Ocean\Xat\Modules;

use Ocean\Xat\API\ActionAPI;
use Ocean\Xat\API\DataAPI;

class OnTickle
{
    public function __invoke($who, $array)
    {
        $bot = ActionAPI::getBot();

        if (!isset($array['t'])) {
            return;
        }

        switch (substr($array['t'], 0, 2)) {
            case '/l':
                $key = 'tickle_' . $array['u'];
                if (!DataAPI::isSetVariable($key)) {
                    DataAPI::set($key, 0);
                }

                // Answer to tickle every 5 seconds
                if (time() - DataAPI::get($key) >= 5) {
                    DataAPI::set($key, time());
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
    }
}
