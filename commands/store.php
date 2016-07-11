<?php

$store = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !store [allpower/everypower/power]', $type, true);
    }

    $message = str_replace(['(', ')'], '', $message);
    $powers = xatVariables::getPowers();
    $exist  = false;

    if (in_array($message[1], ['allpower', 'allpowers'])) {
        $storePrice = 0;
        foreach ($powers as $id => $array) {
            if ($array['isAllPower']) {
                $storePrice += $array['storeCost'];
            }
        }

        $bot->network->sendMessageAutoDetection($who, '"Allpowers" cost ' . number_format($storePrice) . ' xats in store.', $type);
    } elseif (in_array($message[1], ['everypower', 'everypowers'])) {
        $storePrice = 0;
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
            $storePrice = 0;
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
            foreach ($powers as $id => $array) {
                if ($array['name'] == strtolower($message[1]) || $id == $message[1]) {
                    $exist = true;
                    break;
                }
            }

            if (!$exist) {
                return $bot->network->sendMessageAutoDetection($who, 'This power does not exist!', $type);
            }

            if (!isset($array['storeCost'])) {
                $array['storeCost'] = 'is unknown in store. (This power is not added yet).';
            } else {
                $array['storeCost'] = 'costs '.number_format($array['storeCost']).' xats in store.';
            }

            return $bot->network->sendMessageAutoDetection($who, '"'.ucfirst($array['name']).'" ' . $array['storeCost'], $type);
        }
    }
};
