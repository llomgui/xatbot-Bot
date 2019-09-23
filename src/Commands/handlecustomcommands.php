<?php

use xatbot\Utilities;
use xatbot\Bot\XatVariables;

$handlecustomcommands = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();
    $customcommand = substr($message[0], 1);
    $bool = false;

    if (!empty($bot->customcommands)) {
        foreach ($bot->customcommands as $cc) {
            if ($customcommand == $cc['command']) {
                $bool = true;
                if ($bot->flagToRank($who) < $cc['level']) {
                    if (isset($bot->stafflist[$who]) && $cc['level'] <= $bot->stafflist[$who]) {
                    } else {
                        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
                    }
                }

                $latestPower = XatVariables::getLastPower();
                $replace = [
                    '{name}' => $bot->users[$who]->getNick(),
                    '{status}' => $bot->users[$who]->getStatus(),
                    '{regname}' => $bot->users[$who]->getRegname() ?? $bot->users[$who]->getID(),
                    '{id}' => $bot->users[$who]->getID(),
                    '{users}' => sizeof($bot->users),
                    '{online}' => sizeof($bot->users),
                    '{cmdcode}' => $bot->data->customcommand,
                    '{command}' => $bot->data->customcommand,
                    '{cc}' => $bot->data->customcommand,
                    '{randomuser}' => Utilities::arrayRandomAssoc($bot->users, true)[0]->getRegname(),
                    '{randomid}' => Utilities::arrayRandomAssoc($bot->users, true)[0]->getID(),
                    '{randomname}' => Utilities::arrayRandomAssoc($bot->users, true)[0]->getNick(),
                    '{randomnumber}' => rand(0, 1000),
                    '{latestpower}' => ucfirst($latestPower['power']['name']),
                    '{latestpowerid}' => $latestPower['id'],
                    '{latestpowerstoreprice}' => $latestPower['power']['storeCost'],
                    '{latestpowertradeprice}' => ($latestPower['power']['minCost']+$latestPower['power']['maxCost'])/2,
                    '{latestpowerstatus}' => ($latestPower['power']['isNew'] ?
                        ($latestPower['power']['isLimited'] ? 'LIMITED' : 'UNLIMITED') :
                        'UNRELEASED'),
                ];

                $string = $cc['response'];
                foreach ($replace as $key => $value) {
                    $string = str_replace($key, $value, $string);
                }

                return $bot->network->sendMessageAutoDetection($who, $string, $type);
            }
        }
    }

    if (!$bool) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.doesnotexist'), $type);
    }
};
