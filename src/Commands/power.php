<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use OceanProject\Bot\XatVariables;

$power = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'power')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage !power [enable/disable/clear/list] [powername/powerid]',
            $type
        );
    }

    $powers = XatVariables::getPowers();
    switch ($message[1]) {
        case 'enable':
            if (empty($message[2])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage !power [enable/disable/clear/list] [powername/powerid]',
                    $type
                );
            }

            $powerid = null;
            if (is_numeric($message[2])) {
                if (!array_key_exists($message[2], $powers)) {
                    return $bot->network->sendMessageAutoDetection($who, 'This powerID does not exist', $type);
                } else {
                    $powerid = $message[2];
                }
            } else {
                foreach ($powers as $id => $power) {
                    if ($power['name'] == strtolower($message[2])) {
                        $powerid = $id;
                        break;
                    }
                }
            }

            if ($powerid == 93) {
                return $bot->network->sendMessageAutoDetection($who, 'Sorry you can\'t enable MINT power.', $type);
            }

            if (empty($powerid)) {
                return $bot->network->sendMessageAutoDetection($who, 'This power does not exist', $type);
            } else {
                $powersDisabled = json_decode($bot->data->powersdisabled, true);
                if (in_array($powerid, $powersDisabled)) {
                    unset($powersDisabled[array_search($powerid, $powersDisabled)]);
                    $bot->data->powersdisabled = json_encode($powersDisabled);
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection($who, 'Power enabled!', $type);
                    return $bot->refresh();
                } else {
                    return $bot->network->sendMessageAutoDetection($who, 'This power is not disabled!', $type);
                }
            }
            break;

        case 'disable':
            if (empty($message[2])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage !power [enable/disable/clear/list] [powername/powerid]',
                    $type
                );
            }

            $powerid = null;
            if (is_numeric($message[2])) {
                if (!array_key_exists($message[2], $powers)) {
                    return $bot->network->sendMessageAutoDetection($who, 'This powerID does not exist', $type);
                } else {
                    $powerid = $message[2];
                }
            } else {
                foreach ($powers as $id => $power) {
                    if ($power['name'] == strtolower($message[2])) {
                        $powerid = $id;
                        break;
                    }
                }
            }

            if (empty($powerid)) {
                return $bot->network->sendMessageAutoDetection($who, 'This power does not exist', $type);
            } else {
                $powersDisabled = json_decode($bot->data->powersdisabled, true);
                if (!in_array($powerid, $powersDisabled)) {
                    $powersDisabled[] = $powerid;
                    $bot->data->powersdisabled = json_encode($powersDisabled);
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection($who, 'Power disabled!', $type);
                    return $bot->refresh();
                } else {
                    return $bot->network->sendMessageAutoDetection($who, 'This power is not enabled!', $type);
                }
            }
            break;

        case 'clear':
            $bot->data->powersdisabled = json_encode([93]);
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Every Powers enabled!', $type);
            return $bot->refresh();
            break;

        case 'list':
            $powersDisabled = json_decode($bot->data->powersdisabled, true);
            $powerNames = null;
            foreach ($powersDisabled as $powerid) {
                $powerNames[] = $powers[$powerid]['name'];
            }

            return $bot->network->sendMessageAutoDetection(
                $who,
                'List of disabled powers: ' . implode(' ', $powerNames),
                $type
            );
            break;
    }
};
