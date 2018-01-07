<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use xatbot\Bot\XatVariables;

$power = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

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
                    return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.poweridnotexit'), $type);
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
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.power.cantenablemint'), $type);
            }

            if (empty($powerid)) {
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.powernotexist'), $type);
            } else {
                $powersDisabled = json_decode($bot->data->powersdisabled, true);
                if (in_array($powerid, $powersDisabled)) {
                    unset($powersDisabled[array_search($powerid, $powersDisabled)]);
                    $bot->data->powersdisabled = json_encode($powersDisabled);
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.power.powerenabled'), $type);
                    return $bot->refresh();
                } else {
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.power.isnotdisabled'),
                        $type
                    );
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
                    return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.poweridnotexit'), $type);
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
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.powernotexist'), $type);
            } else {
                $powersDisabled = json_decode($bot->data->powersdisabled, true);
                if (!in_array($powerid, $powersDisabled)) {
                    $powersDisabled[] = $powerid;
                    $bot->data->powersdisabled = json_encode($powersDisabled);
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.power.powerdisabled'), $type);
                    return $bot->refresh();
                } else {
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.power.isnotenabled'),
                        $type
                    );
                }
            }
            break;

        case 'clear':
            $bot->data->powersdisabled = json_encode([93]);
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.power.every'), $type);
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
                $bot->botlang('cmd.power.powerslist', [implode(', ', $powerNames)]),
                $type
            );
            break;
    }
};
