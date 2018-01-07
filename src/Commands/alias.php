<?php

use xatbot\Utilities;
use xatbot\Models\Aliases;

$alias = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'alias')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !in_array($message[1], ['add', 'remove', 'rm'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !alias [add/remove] [alias] [currentcommand]',
            $type
        );
    }

    
    switch ($message[1]) {
        case 'add':
            if (isset($message[2]) && !empty($message[2])) {
                if (isset($message[3]) && !empty($message[3])) {
                    $alias = ctype_alnum($message[2][0]) ? $message[2] : substr($message[2], 1);
                    $currentcommand = ctype_alnum($message[3][0]) ? $message[3] : substr($message[3], 1);
                    
                    // Checking if the alias is not already in use
                    foreach ($bot->aliases as $key => $value) {
                        if (strtolower($alias) == $key) {
                            return $bot->network->sendMessageAutoDetection(
                                $who,
                                $bot->botlang('cmd.alias.alreadyused'),
                                $type
                            );
                        }
                    }
                    
                    // Someone took a cmd in 2 words
                    $newCurrent = '';
                    if (isset($message[4])) {
                        $newCurrent = ($currentcommand . ' ' . $message[4]);
                    }
                    
                    $alias = strtolower($alias);
                    $currentcommand = strtolower($currentcommand);
                    
                    // Checking if the alias is a command..
                    if (isset($bot->minranks[$alias])) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('cmd.alias.alreadycommand'),
                            $type
                        );
                    }
                    
                    // Checking if the current command is actually a command on bot
                    if (!isset($bot->minranks[$currentcommand])) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('cmd.alias.notcommand'),
                            $type
                        );
                    }
                    
                    $aliases = new Aliases;
                    $aliases->bot_id = $bot->data->id;
                    $aliases->command = ($newCurrent == '' ? $currentcommand : $newCurrent);
                    $aliases->alias = $alias;
                    $aliases->save();
                    
                    $bot->aliases = $bot->setAliases();
                    
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.alias.added'),
                        $type
                    );
                }
            }
            break;
        case 'rm':
        case 'remove':
            if (isset($message[2]) && !empty($message[2]) && is_int((int)$message[2])) {
                foreach ($bot->aliases as $key => $value) {
                    if (strtolower($message[2]) == $key) {
                        Aliases::where([
                          ['alias', '=', strtolower($message[2])],
                          ['bot_id', '=', $bot->data->id]
                        ])->delete();
                        
                        $bot->aliases = $bot->setAliases();
                        
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('cmd.alias.removed', [$message[2]]),
                            $type
                        );
                    }
                }
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.alias.notinlist'),
                    $type
                );
            }
            break;
    }
};
