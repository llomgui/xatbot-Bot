<?php

$richest = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'richest')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $powers = OceanProject\Bot\XatVariables::getPowers();

    foreach ($bot->users as $user) {
        if (!is_object($user)) {
            continue;
        }

        if (!$user->hasDays()) {
            continue;
        }

        $doubles    = $user->getDoubles();
        $storeprice = 0;
        $minprice   = 0;
        $maxprice   = 0;
        $count      = 0;

        if (isset($doubles)) {
            $pO = explode('|', $doubles);

            for ($i = 0; $i < sizeof($pO); $i++) {
                $pos = strpos($pO[$i], '=');
                if ($pos !== false) {
                    $id      = (int)substr($pO[$i], 0, $pos);
                    $ammount = (int)substr($pO[$i], $pos + 1);
                } else {
                    $id      = (int)$pO[$i];
                    $ammount = 1;
                }

                if ($id == 0) {
                    continue;
                }

                if (isset($powers[$id]['storeCost'])) {
                    if (!$powers[$id]['isLimited']) {
                        $storeprice += $powers[$id]['storeCost'] * $ammount;
                    }
                }

                $minprice   += $powers[$id]['minCost'] * $ammount;
                $maxprice   += $powers[$id]['maxCost'] * $ammount;
                $count      += $ammount;
            }
        }

        foreach ($powers as $id => $array) {
            if ($id == 95) {
                continue;
            }

            if ($user->hasPower($id)) {
                if (isset($array['storeCost'])) {
                    if (!$array['isLimited']) {
                        $storeprice += $array['storeCost'];
                    }
                }

                $minprice   += $array['minCost'];
                $maxprice   += $array['maxCost'];
                $count++;
            }
        }

        $res[] = ['max' => $maxprice, 'min' => $minprice, 'user' => $user];
        sort($res);
    }

    if (empty($res)) {
        return $bot->network->sendMessageAutoDetection($who, 'There is no user with days in this chat :(.', $type);
    }

    $bot->network->sendMessageAutoDetection(
        $who,
        'The richest user in this room is ' . $res[sizeof($res) - 1]['user']->getRegname().'('.
            $res[sizeof($res) - 1]['user']->getID().').',
        $type
    );
};
