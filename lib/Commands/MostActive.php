<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class MostActive
{
    public function __invoke($who, $message, $type)
    {
        $bot  = ActionAPI::getBot();
        $now  = time();
        $most = ['user' => null, 'time' => 0];

        foreach ($bot->users as $user) {
            if (!is_object($user) || !DataAPI::isSetVariable($who . '_active')) {
                continue;
            }

            $userTime = $now - DataAPI::get($who . '_active');

            // Maybe implement a way to show more then 1 user if activetime is equal?
            if ($userTime > $most['time']) {
                $most = ['user' => $user, 'time' => $userTime];
            }
        }

        $displayName = $most['user']->isRegistered() ?
                            $most['user']->getRegname() . '(' . $most['user']->getID() . ')' :
                            $most['user']->getID();

        $bot->network->sendMessageAutoDetection(
            $who,
            'The current most active user is ' . $displayName . ' with a time of ' . $bot->secondsToTime($userTime),
            $type
        );
    }
}
