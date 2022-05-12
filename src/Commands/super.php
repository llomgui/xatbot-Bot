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
            'Usage !super [anime/summer/halloween/xmas/heart/hobbies/scary/santa/love/egg/kao] [user]',
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
            'ani1' => 253,
            'anime' => 53,
            'animegirl' => 392,
            'aprincess' => 399,
            'battle' => 420,
            'blueoni' => 311,
            'coolz' => 298,
            'cutie' => 295,
            'manga' => 151,
        ];

        $summer = [
            'beach' => 128,
            'seaside' => 229,
            'shells' => 345,
            'splashfx' => 445,
            'summer' => 89,
            'summerflix' => 296,
            'summerhug' => 401,
            'summerland' => 398,
            'vacation' => 343,
        ];

        $halloween = [
            'allhallows' => 362,
            'carve' => 147,
            'creepy' => 309,
            'halloscroll' => 465,
            'halloween' => 52,
            'halloween2' => 257,
            'horror' => 92,
            'muertos' => 518,
            'trickortreat' => 308,
            'witch' => 363,
        ];

        $xmas = [
            'choirhug' => 366,
            'christmas' => 57,
            'christmix' => 418,
            'kxmas' => 470,
            'ornaments' => 368,
            'snowy' => 56,
            'sparklefx' => 419,
            'treefx' => 203,
            'tropicalxmas' => 417,
            'winterland' => 261,
            'winter' => 96,
            'xmasscroll' => 471,
            'xmastime' => 577,
        ];

        $heart = [
            'amore' => 324,
            'burningheart' => 193,
            'heart' => 17,
            'heartfx' => 166,
            'kheart' => 215,
            'sweetheart' => 271,
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

        // 148,202,213,254,256,270,354,411,412,446,454,464,565,567
        $scary = [
            'spookies' => 567,
            'fear' => 565,
        ];

        // 154,155,156,204,262,263,314,315,367,475,571,573
        $santa = [
            'neva' => 573,
            'kris' => 571,
        ];

        // 62,241,233,242,335,374,425,426,427,482,583
        $love = [
            'lovepotion' => 583,
        ];

        // 222,251,281,332,381,440,544,587
        $egg = [
            'yellegg' => 587,
        ];

        // 72,76,248,249,305,306,358,361,372,385,422,451,456,499,507,520,529,527,542
        $kao = [
            'kbacks' => 542,
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
