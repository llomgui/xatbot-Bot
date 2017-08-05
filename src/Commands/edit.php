<?php

$edit = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'edit')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !edit [nickname/avatar/homepage/status/pcback/autowelcome/ticklemessage/moderation/customcommand] [info]',
            $type,
            true
        );
    }

    switch ($message[1]) {
        case 'nickname':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $message = str_replace(' ', '', $message);
            $bot->data->nickname = $message;
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Nickname is updated!', $type, true);
            $bot->refresh();
            break;

        case 'avatar':
            $bot->data->avatar = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Avatar is updated!', $type, true);
            $bot->refresh();
            break;

        case 'homepage':
            $bot->data->homepage = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Homepage is updated!', $type, true);
            $bot->refresh();
            break;

        case 'status':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->data->status = $message;
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Status is updated!', $type, true);
            $bot->refresh();
            break;

        case 'pcback':
            $bot->data->pcback = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'PcBack is updated!', $type, true);
            $bot->refresh();
            break;

        case 'autowelcome':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->data->autowelcome = $message;
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Autowelcome is updated!', $type, true);
            break;

        case 'ticklemessage':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->data->ticklemessage = $message;
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Tickle Message is updated!', $type, true);
            break;
            
        case 'moderation':
            switch (strtolower($message[2])) {
                case 'on':
                    if ($bot->data->togglemoderation == true) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            'Moderation is already enabled!',
                            $type
                        );
                    }
                    $bot->data->togglemoderation = true;
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection(
                        $who,
                        'Moderation has been turned on!',
                        $type
                    );
                    break;
              
                case 'off':
                    if ($bot->data->togglemoderation == false) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            'Moderation is already disabled!',
                            $type
                        );
                    }
                    $bot->data->togglemoderation = false;
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection(
                        $who,
                        'Moderation has been turned off!',
                        $type
                    );
                    break;
                
                default:
                    $bot->network->sendMessageAutoDetection(
                        $who,
                        'Usage: !edit moderation [on/off] | Moderation is currently ' . ($bot->data->togglemoderation ? 'enabled' : 'disabled') . '.',
                        $type
                    );
                    break;
            }
            break;

        case 'customcommand':
            if (strlen($message[2]) > 1) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'The max length of customcommand is 1.',
                    $type,
                    true
                );
            }
            $bot->data->customcommand = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, 'Custom Command is updated!', $type, true);
            break;
        
        default:
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !edit [nickname/avatar/homepage/status/pcback/autowelcome/ticklemessage/customcommand] [info]',
                $type,
                true
            );
            break;
    }
};
