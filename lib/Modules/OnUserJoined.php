<?php

namespace Ocean\Xat\Modules;

use Ocean\Xat\API\ActionAPI;
use Ocean\Xat\API\DataAPI;
use Ocean\Xat\User;

class OnUserJoined
{
    public function __invoke($who, $array)
    {
        $bot = ActionAPI::getBot();

        if ($who >= 1900000000) {
            return;
        }

        $bot->users[$who] = new User($array);
        $user = $bot->users[$who];

        if ($user->isRegistered() && !$user->isAway() && !$user->wasHere()) {
            $bot->network->sendTickle($who);
        }

        if (!DataAPI::isSetVariable($who . '_joined')) {
            DataAPI::set($who . '_joined', false);
        }

        if (!DataAPI::isSetVariable($who . '_active')) {
            DataAPI::set($who . '_active', time());
        }

        if (DataAPI::isSetVariable($who . '_left')) {
            if (DataAPI::get($who . '_left') - 300 > DataAPI::get($who . '_active')) {
                DataAPI::set($who . '_active', time());
                DataAPI::unSetVariable($who . '_left', time());
            }
        }

        if ($user->isAway() or DataAPI::get('away_' . $user->getID())) {
            DataAPI::set('away_' . $user->getID(), $user->isAway());
            return;
        }

        return;
    }
}
