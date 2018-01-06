<?php

$store = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'store')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !store [allpower/everypower/power]', $type, true);
    }

    $message = str_replace(['(', ')'], '', $message);
    $powers = OceanProject\Bot\XatVariables::getPowers();
    $exist  = false;
    $storePrice = 0;

    if (in_array($message[1], ['allpower', 'allpowers'])) {
        foreach ($powers as $id => $array) {
            if ($array['isAllPower']) {
                $storePrice += $array['storeCost'];
            }
        }

        $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.store.allpowers', [
                number_format($storePrice)
            ]),
            $type
        );
    } elseif (in_array($message[1], ['everypower', 'everypowers'])) {
        foreach ($powers as $id => $array) {
            if ($id == 95) {
                continue;
            } else {
                $storePrice += $array['storeCost'];
            }
        }

        $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.store.everypower', [
                number_format($storePrice)
            ]),
            $type
        );
    } else {
        if (isset($message[2]) && !empty($message[2])) {
            unset($message[0]);
            foreach ($message as $mess) {
                if (!empty($mess)) {
                    foreach ($powers as $id => $array) {
                        if ($array['name'] == strtolower($mess) || $id == $mess) {
                            $storePrice += $array['storeCost'];
                            $exist       = true;
                        }
                    }
                }
            }

            if (!$exist) {
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.powernotexist'), $type);
            }

            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.store.thosepowers', [$storePrice]),
                $type
            );
        } else {
            $match = $bot->network->findPowerMatch($message[1]);
            $powerID = $match[0];

            if (!$powerID) {
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.powernotexist'), $type);
            }

            if (!isset($powers[$powerID]['storeCost'])) {
                $powers[$powerID]['storeCost'] = $bot->botlang('cmd.store.isunknown');
            } else {
                $powers[$powerID]['storeCost'] = $bot->botlang('cmd.store.message', [
                    number_format($powers[$powerID]['storeCost'])
                ]);
            }
            $dym = $match[1] === false ? $bot->botlang('cmd.didyoumean', [$powers[$powerID]['name']]) : '';
            return $bot->network->sendMessageAutoDetection(
                $who,
                $dym . '"'.ucfirst($powers[$powerID]['name']).'" ' . $powers[$powerID]['storeCost'],
                $type
            );
        }
    }
};
