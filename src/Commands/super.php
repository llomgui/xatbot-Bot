<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$super = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();
    if (!$bot->minrank($who, 'super')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage !super [anime/summer/halloween/xmas/heart/hobbies] [user]',
            $type
        );
    }

    if (empty($message[2])) {
        $message[2] = $who;
    }

    $info = null;
    if (is_numeric($message[2])) {
        $message[2] = (int)$message[2];

        if ($message[2] == 9223372036854775807) {
            return $bot->network->sendMessageAutoDetection($who, 'I am in a 64-bit environment.', $type, true);
        }
        
        $info = Capsule::table('userinfo')
                ->where('xatid', $message[2])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->toArray();
    } else {
        $info = Capsule::table('userinfo')
                ->whereRaw('LOWER(regname) = ?', [strtolower($message[2])])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->toArray();
    }

    if (!empty($info)) {
        $info = $info[0];
        
        $anime = [
            'anime' => 53,
            'manga' => 151,
            'ani1' => 253,
            'cutie' => 295,
            'coolz' => 298,
            'blueoni' => 311,
            'animegirl' => 392,
            'aprincess' => 399,
            'battle' => 420,
        ];

        $summer = [
            'summer' => 89,
            'beach' => 128,
            'seaside' => 229,
            'summerflix' => 296,
            'vacation' => 343,
            'shells' => 345,
            'summerland' => 398,
            'summerhug' => 401,
            'splashfx' => 445,
        ];

        $halloween = [
            'halloween' => 52,
            'horror' => 92,
            'carve' => 147,
            'halloween2' => 257,
            'trickortreat' => 308,
            'creepy' => 309,
            'allhallows' => 362,
            'witch' => 363,
            'halloscroll' => 465,
            'muertos' => 518,
        ];

        $xmas = [
            'snowy' => 56,
            'christmas' => 57,
            'winter' => 96,
            'treefx' => 203,
            'winterland' => 261,
            'choirhug' => 366,
            'ornaments' => 368,
            'tropicalxmas' => 417,
            'christmix' => 418,
            'sparklefx' => 419,
            'kxmas' => 470,
            'xmasscroll' => 471,
        ];

        $heart = [
            'heart' => 17,
            'heartfx' => 166,
            'burningheart' => 193,
            'kheart' => 215,
            'sweetheart' => 271,
            'amore' => 324,
            'valfx' => 325,
            'valentinefx' => 532,
        ];

        $hobbies = [
            'fashion' => 536,
            'mime' => 540,
            'tshirt' => 547,
            'swimming' => 550,
            'acting' => 557,
        ];

        $powersToCheck = ${$message[1]};

        $user = new xatbot\Bot\XatUser(json_decode($info->packet, true));
        $powersmissing = [];
        if ($user->hasDays()) {
            foreach ($powersToCheck as $powerName => $powerId) {
                if (!$user->hasPower($powerId)) {
                    $powersmissing[] = $powerName;
                }
            }

            if (empty($powersmissing)) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $info->regname . ' is not missing any powers to have (super' . $message[1] . ').',
                    $type
                );
            } else {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $info->regname . ' is missing ' . implode(', ', $powersmissing) . ' to have (super' .
                        $message[1] . ').',
                    $type
                );
            }
        } else {
            return $bot->network->sendMessageAutoDetection($who, 'This user does not have days.', $type);
        }
    } else {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.notindatabase'), $type);
    }
};
