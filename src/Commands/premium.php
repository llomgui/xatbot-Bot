<?php

$premium = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'premium')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !premium [time/freeze/unfreeze]', $type);
    }

    switch ($message[1]) {
        case 'time':
            if ($bot->isPremium) {
                $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.premium.ispremiumfor', [$bot->sec2hms($bot->data->premium - time())]),
                    $type
                );
            } elseif ($bot->data->premiumfreeze > 1) {
                $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.premium.isfrozen'), $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.premium.notpremium'), $type);
            }
            break;

        case 'freeze':
            if ($bot->data->premium < time()) {
                $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.premium.notpremium'), $type);
            } elseif ($bot->data->premiumfreeze > 1) {
                $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.premium.alreadyfrozen'), $type);
            } else {
                $bot->data->premiumfreeze = $bot->data->premium - time();
                $bot->data->save();
                $bot->refresh();
                $bot->network->sendMessageAutoDetection($who, $bot->botLang('cmd.premium.nowfrozen'), $type);
            }
            break;

        case 'unfreeze':
            if ($bot->data->premiumfreeze > 1) {
                $bot->data->premium = time() + $bot->data->premiumfreeze;
                $bot->data->premiumfreeze = 1;
                $bot->data->save();
                $bot->refresh();
                $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.premium.nowunfrozen'), $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.premium.notfrozen'), $type);
            }
            break;

        default:
            $bot->network->sendMessageAutoDetection($who, 'Usage: !premium [time/freeze/unfreeze]', $type);
            break;
    }
};
