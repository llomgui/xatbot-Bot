<?php

use OceanProject\Models\Language;

$edit = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'edit')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (isset($message[1]) && ($message[1] == 'language') && (empty($message[2]))) {
        $languagesList = Language::all();
        $languages = [];
        foreach ($languagesList as $langue) {
            $languages[$langue->code] = $langue->id;
        }

        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !edit language [' . implode('/', array_keys($languages)) . ']',
            $type
        );
    }

    if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !edit [nickname/avatar/homepage/status/pcback/autowelcome/ticklemessage/customcommand] [info]',
            $type
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
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.nickname'), $type, true);
            $bot->refresh();
            break;

        case 'avatar':
            $bot->data->avatar = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.avatar'), $type, true);
            $bot->refresh();
            break;

        case 'homepage':
            $bot->data->homepage = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.homepage'), $type, true);
            $bot->refresh();
            break;

        case 'status':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->data->status = $message;
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.status'), $type, true);
            $bot->refresh();
            break;

        case 'pcback':
            $bot->data->pcback = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.pcback'), $type, true);
            $bot->refresh();
            break;

        case 'autowelcome':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->data->autowelcome = $message;
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.autowelcome'), $type, true);
            break;

        case 'ticklemessage':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $bot->data->ticklemessage = $message;
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.ticklemessage'), $type, true);
            break;

        case 'language':
            $languagesList = Language::all();
            foreach ($languagesList as $langue) {
                if ($langue->code == strtolower($message[2]) ||
                        strtolower($langue->name) == strtolower($message[2])) {
                    $bot->data->language_id = $langue->id;
                    $bot->data->save();
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.edit.languageupdated'),
                        $type
                    );
                }
            }
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.edit.languagenotfound'),
                $type
            );
            break;
            
        case 'moderation':
            switch (strtolower($message[2])) {
                case 'on':
                    if ($bot->data->togglemoderation == true) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('cmd.edit.modalreadyenabled'),
                            $type
                        );
                    }
                    $bot->data->togglemoderation = true;
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.edit.modenabled'),
                        $type
                    );
                    break;
              
                case 'off':
                    if ($bot->data->togglemoderation == false) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            $bot->botlang('cmd.edit.modalreadydisabled'),
                            $type
                        );
                    }
                    $bot->data->togglemoderation = false;
                    $bot->data->save();
                    $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.edit.moddisabled'),
                        $type
                    );
                    break;
                
                default:
                    $bot->network->sendMessageAutoDetection(
                        $who,
                        'Usage: !edit moderation [on/off]',
                        $type
                    );
                    break;
            }
            break;

        case 'customcommand':
            if (strlen($message[2]) > 1) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.edit.customcommandmaxlength'),
                    $type,
                    true
                );
            }
            $bot->data->customcommand = $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.edit.customcommand'), $type, true);
            break;

        case 'kickafk':
            if (!is_numeric($message[2]) || (int) $message[2] < 5 || (int) $message[2] > 120) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !edit kickafk [time] (Min: 5 minutes - Max: 120 minutes)',
                    $type
                );
            }
            $bot->data->kickafk_minutes = (int) $message[2];
            $bot->data->save();
            $bot->network->sendMessageAutoDetection(
                $who, 
                'The kickafk time has been changed to ' . (int) $message[2] . ' minutes.',
                $type
            );
            break;
        
        default:
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !edit [nickname/avatar/homepage/status/pcback/autowelcome/ticklemessage/customcommand] [info]',
                $type
            );
            break;
    }
};
