<?php

$store = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'store')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !store [allpower/everypower/power]', $type, true);
    }

    $message = str_replace(['(', ')'], '', $message);
    $powers = xatVariables::getPowers();
    $exist  = false;
    $storePrice = 0;

    if (in_array($message[1], ['allpower', 'allpowers'])) {
        foreach ($powers as $id => $array) {
            if ($array['isAllPower']) {
                $storePrice += $array['storeCost'];
            }
        }

        $bot->network->sendMessageAutoDetection($who, '"Allpowers" cost ' . number_format($storePrice) . ' xats in store.', $type);
    } elseif (in_array($message[1], ['everypower', 'everypowers'])) {
        foreach ($powers as $id => $array) {
            if ($id == 95) {
                continue;
            } else {
                $storePrice += $array['storeCost'];
            }
        }

        $bot->network->sendMessageAutoDetection($who, '"Everypower" costs ' . number_format($storePrice) . ' xats in store.', $type);
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
                return $bot->network->sendMessageAutoDetection($who, 'This power does not exist!', $type);
            }

            return $bot->network->sendMessageAutoDetection($who, 'Those powers cost ' . $storePrice . ' xats in store.', $type);
        } else {
            $match = $bot->network->findPowerMatch($message[1]);
            $powerID = $match[0];

            if (!$powerID) {
                return $bot->network->sendMessageAutoDetection($who, 'This power does not exist!', $type);
            }

            if (!isset($powers[$powerID]['storeCost'])) {
                $powers[$powerID]['storeCost'] = 'is unknown in store. (This power is not added yet).';
            } else {
                $powers[$powerID]['storeCost'] = 'costs '.number_format($powers[$powerID]['storeCost']).' xats in store.';
            }
            $dym = $match[1] === false ? 'Did you mean "' . $powers[$powerID]['name'] . '"? ' : '';
            return $bot->network->sendMessageAutoDetection($who, $dym . '"'.ucfirst($powers[$powerID]['name']).'" ' . $powers[$powerID]['storeCost'], $type);
        }
    }
};