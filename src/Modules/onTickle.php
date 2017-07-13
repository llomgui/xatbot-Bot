<?php

use OceanProject\API\DataAPI;
use OceanProject\Bot\XatVariables;
use OceanProject\Userinfo;

$onTickle = function (int $who, array $array) {

    $bot = OceanProject\API\ActionAPI::getBot();

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

                if ($bot->isPremium) {
                    $bot->network->answerTickle($who);
                }

                if (!empty(trim($bot->data->ticklemessage))) {
                    $bot->network->sendPrivateMessage($who, $bot->data->ticklemessage);
                }
            } elseif (time() - DataAPI::get($key) <= 5) {
                DataAPI::set($key, time());
                if ($bot->data->gameban_unban == 1) {
                    if (isset($bot->users[$who])
                    && is_object($bot->users[$who])
                    && $bot->users[$who]->isGamebanned()) {
                        $powers = XatVariables::getPowers();
                        $bot->network->unban($who);
                        $bot->network->sendMessage(
                            $bot->users[$who]->getRegname() . ' rapid tickled me to get unbanned from the gameban ' .
                            $powers[$bot->users[$who]->getGameban()]['name'] . '.'
                        );
                    }
                }
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
            
            $bot->users[$who]->setMaskedPowers($array);

            $userinfo = Userinfo::getUserinfo();
            $userinfo[$who]['xatid'] = $who;
            $userinfo[$who]['regname'] = $bot->users[$who]->getRegname();
            $userinfo[$who]['chatid'] = $bot->data->chatid;
            $userinfo[$who]['chatname'] = $bot->data->chatname;
            $userinfo[$who]['packet'] = json_encode($array);
            Userinfo::setUserinfo($userinfo);

            break;
    }

    return;
};
