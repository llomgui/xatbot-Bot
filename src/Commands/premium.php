<?php

$premium = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'premium')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    switch ($message[1]) {
        case 'time':
            if ($bot->isPremium) {
                $bot->network->sendMessageAutoDetection(
                    $who,
                    'I am premium for the next ' . $bot->sec2hms($bot->data->premium - time()) . '.',
                    $type
                );
            } elseif ($bot->data->premiumfreeze > 1) {
                $bot->network->sendMessageAutoDetection($who, 'I am frozen.', $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, 'I am not premium!', $type);
            }
            break;

        case 'freeze':
            if ($bot->data->premium < time()) {
                $bot->network->sendMessageAutoDetection($who, 'I am not premium, you cannot freeze me.', $type);
            } elseif ($bot->data->premiumfreeze > 1) {
                $bot->network->sendMessageAutoDetection($who, 'I am already frozen.', $type);
            } else {
                $bot->data->premiumfreeze = $bot->data->premium - time();
                $bot->data->save();
                $bot->refresh();
                $bot->network->sendMessageAutoDetection($who, 'I am now frozen.', $type);
            }
            break;

        case 'unfreeze':
            if ($bot->data->premiumfreeze > 1) {
                $bot->data->premium = time() + $bot->data->premiumfreeze;
                $bot->data->premiumfreeze = 1;
                $bot->data->save();
                $bot->refresh();
                $bot->network->sendMessageAutoDetection($who, 'I am now un-frozen.', $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, 'I am not frozen.', $type);
            }
            break;

        default:
            $bot->network->sendMessageAutoDetection($who, 'Usage: !premium [time/freeze/unfreeze]', $type);
            break;
    }
};
