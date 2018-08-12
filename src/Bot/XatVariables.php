<?php

namespace xatbot\Bot;

abstract class XatVariables
{
    private static $init;
    private static $update;

    private static $regname;
    private static $xatid;
    private static $password;
    private static $pin;
    private static $pw;
    private static $forcelogin;

    private static $ip2;
    private static $powers;
    private static $volunteers;
    private static $freeSmilies;
    private static $developers;

    private static $loginTime;
    private static $loginPacket;

    private static $releaseTime;
    private static $adMessage1;
    private static $adMessage2;
    
    private static $defaultName;
    
    private static $apikeys;
    private static $config;

    public static function init()
    {
        if (self::$init) {
            return false;
        }

        self::initBotAccount();
        self::initIP2();
        self::initVolunteers();
        self::initFreeSmilies();
        self::initPowers();
        self::initDefaultName();
        self::initDevelopers();
        self::initAPIKeys();
        self::initConfig();

        return self::$init = true;
    }

    private static function initBotAccount()
    {
        $data = json_decode(file_get_contents('./config.json', true), true);

        self::$regname    = $data['botaccount']['regname'];
        self::$xatid      = $data['botaccount']['xatid'];
        self::$password   = $data['botaccount']['password'];
        self::$pin        = $data['botaccount']['pin'];
        self::$pw         = $data['botaccount']['pw'];
        self::$forcelogin = $data['botaccount']['forcelogin'];
    }

    private static function initIP2()
    {
        $ip2 = [
            'S0' => [
                0, [array_fill(0, 15, 's.xat.com')]
            ],
            'order' => [
                ['E0' => 60], ['E1' => 90], ['E0' => 180], ['E1' => 240], ['S0' => 240]
            ],
            'xFlag' => 2,
            'time' => time(),
            'T0' => [
                1, ['']
            ],
            'E0' => [
                1, [
                    'fwdelb00-1964376362.us-east-1.elb.amazonaws.com',
                    'fwdelb01-1365137239.us-east-1.elb.amazonaws.com',
                    'fwdelb02-53956973.us-east-1.elb.amazonaws.com',
                    'fwdelb03-1789285345.us-east-1.elb.amazonaws.com'
                ]
            ],
            'E1' => [
                1, [
                    'fwdelb00-1964376362.us-east-1.elb.amazonaws.com',
                    'fwdelb01-1365137239.us-east-1.elb.amazonaws.com',
                    'fwdelb02-53956973.us-east-1.elb.amazonaws.com',
                    'fwdelb03-1789285345.us-east-1.elb.amazonaws.com'
                ]
            ],
            'k1n' => 2000000000,
            'k1d' => 14
        ];

        self::$ip2 = $ip2;
    }

    private static function initVolunteers()
    {
        $volunteers = [
            ['regname' => 'Andre',   'xatid' => 112633],
            ['regname' => 'Angelo',  'xatid' => 18500000],
            ['regname' => 'Brandon', 'xatid' => 1010208],
            ['regname' => 'Crow',    'xatid' => 4444],
            ['regname' => 'Cupim',   'xatid' => 10000690],
            ['regname' => 'Cyan',    'xatid' => 283021491],
            ['regname' => 'Echo',    'xatid' => 182000000],
            ['regname' => 'Kyle',    'xatid' => 96321545],
            ['regname' => 'Mihay',   'xatid' => 1700000],
            ['regname' => 'Sydno',   'xatid' => 220711],
            ['regname' => 'Vale',    'xatid' => 32646043]
        ];

        self::$volunteers = $volunteers;
    }
    
    private static function initDefaultName()
    {
        $name1 = [
          'Baby', 'Booble', 'Bunker', 'Cuddle',
          'Cutie', 'Doodle', 'Foofie', 'Gooble',
          'Honey', 'Kissie', 'Lover', 'Lovey',
          'Moofie', 'Mooglie', 'Moopie', 'Moopsie',
          'Nookum', 'Poochie', 'Pookie', 'Schmoopie',
          'Schnoogle', 'Schnookie', 'Schnookum', 'Smooch',
          'Smoochie', 'Smoosh', 'Snoogle', 'Snoogy',
          'Snookie', 'Snookum', 'Snuggy', 'Sweetie',
          'Woogle', 'Woogy', 'Wookie', 'Wookum',
          'Wuddle', 'Wuggy', 'Wunny', 'Bumble',
          'Bump', 'Dip'
        ];
        $name2 = [
          'Boo', 'Bunch', 'Bunny', 'Cake',
          'Cakes', 'Cute', 'Darling', 'Dumpling',
          'Dumplings', 'Face', 'Foof', 'Goo',
          'Head', 'Kin', 'Kins', 'Lips',
          'Love', 'Mush', 'Pie', 'Pook',
          'Pums', 'Bumble', 'Bump', 'Dip'
        ];
  
        foreach ($name1 as $n1) {
            foreach ($name2 as $n2) {
                $defaultName[] = $n1.$n2;
            }
        }
        
        self::$defaultName = $defaultName;
    }

    private static function initFreeSmilies()
    {
        $group1_smilies = [
            'smile|tongue|biggrin|mad|confused|redface|crying|frown|eek|wink',
            '3d|cool|6|a|sry|crs|un|d|scn|nod',
            'gagged|nme|swt|roll|rofl|chkl|inlove|blk|xp|eyes',
            'tired|smirk|ill|dead|hello|yum|think|mischief|zip2|puke',
            'yawn|swear|cry2|what|omg|o_o|goo|smirk2|beye|wary',
            'shock|xd|cyc|wt|chew|contempt|fedup|aghast|look|spin2',
            'tipsy|twitch|shifty|ashamed|pty|sad|rolling|no|jolly|annoyed',
            'astonished|evil|sinister|sour|um|dream|memory|babythrow|candyeat|cute',
            'flowerthrow|gloomy|hey|hula|loving|meloneat|shadesoff|stressing|waiting|hmm',
            'meh|ugh|hi|applause|donttalk|joyful|smug|victory|blowkiss|point',
            'dull|medmask|sob|wacky|ecstatic|ok|sweat|wailing|content|farewell',
            'pensive|tearhair|weary|daydream|flustered|tmi|whatever|furious|sarcastic|toj',
            'whistling|depressed|here|screaming|ttm|zany|desire|hih|sidetear|unamused',
            'dollar|idc|slap|confounded|unreal|deal|hug|uhuh|pirate2|hehe',
            'clap|shrug|prop'
        ];

        $group2_smilies = [
            'ugc|hearts2|peach|xana|x|chest|gst|alien|bby|bot1',
            'tox|8ball|eye|kirb|pm|pmg|inv|inv2|inv3|sonic',
            'shadow|mario|luigi|mushroom|yoshi|countb|ness|smashball|mephiles|tri',
            'lucario|nights|arbiter|mouser|mewtwo|xj9|hk|lolwut|kermit|beaker',
            'beast|dv|homer|3tomoe|mangekyou|pikachu|pball|wwe|sm|a1',
            'nko|x3|dog|cat|pig|mk|penguin|panda|bear|cc',
            'ccc|cotton|pie|c|b|o|i|t|mo|so',
            'sb|oo|p|ph|yt|dmd|bin|ush|ipod|ip',
            'sun|rain|r|f|li|l|u|y|n|grl',
            'boy|scb|bio|rad|pgm|mgp|ao|star|note|hex',
            'yy|moon|rubik|cir|cdy|deer|snta|g|sman|xday',
            'xtre|xstk|mtoe|hly|egg|ghat|clover|stickman|stickman2|stickman3',
            'stickairguitar|stickkungfu|stickangry|stickymca|danny|turkey|ss|qbone|mc|lb',
            'mario8|nop|okp|mate|confetti|champagne|argue|cry|snow|gifts',
            'hippo|paint|surprise|bugs|magic8ball|airplane|parachute|dynamite|globe|grumpy',
            'lips|bomb|fireworks|pull|shark|blood|ca|censor'
        ];

        $yellow_smilies = [];
        foreach ($group1_smilies as $group1_smilie) {
            $foo = explode('|', $group1_smilie);
            for ($i = 0; $i < sizeof($foo); $i++) {
                $yellow_smilies[] = $foo[$i];
            }
        }

        $other_smilies  = [];
        foreach ($group2_smilies as $group2_smilie) {
            $foo = explode('|', $group2_smilie);
            for ($i = 0; $i < sizeof($foo); $i++) {
                $other_smilies[] = $foo[$i];
            }
        }

        self::$freeSmilies = $yellow_smilies + $other_smilies;
    }

    private static function initPowers()
    {
        $powers[1]['name']    = 'topman';
        $powers[1]['minCost'] = 0;
        $powers[1]['maxCost'] = 0;
        $powers[1]['smilies'] = ['topman'];

        $powers[2]['name']    = 'subhide';
        $powers[2]['minCost'] = 0;
        $powers[2]['maxCost'] = 0;
        $powers[2]['smilies'] = ['subhide'];

        $powers[3]['name']    = 'mod8';
        $powers[3]['minCost'] = 0;
        $powers[3]['maxCost'] = 0;
        $powers[3]['smilies'] = ['mod8'];

        $powers[4]['name']    = 'zoom';
        $powers[4]['minCost'] = 0;
        $powers[4]['maxCost'] = 0;
        $powers[4]['smilies'] = ['zoom'];

        $powers[5]['name']    = 'nofollow';
        $powers[5]['minCost'] = 0;
        $powers[5]['maxCost'] = 0;
        $powers[5]['smilies'] = ['nofollow'];

        $powers[6]['name']    = 'invert';
        $powers[6]['minCost'] = 0;
        $powers[6]['maxCost'] = 0;
        $powers[6]['smilies'] = ['invert'];

        $powers[7]['name']    = 'mirror';
        $powers[7]['minCost'] = 0;
        $powers[7]['maxCost'] = 0;
        $powers[7]['smilies'] = ['mirror'];

        $powers[8]['name']    = 'noaudies';
        $powers[8]['minCost'] = 0;
        $powers[8]['maxCost'] = 0;
        $powers[8]['smilies'] = ['noaudies'];

        $powers[9]['name']    = 'reghide';
        $powers[9]['minCost'] = 0;
        $powers[9]['maxCost'] = 0;
        $powers[9]['smilies'] = ['reghide'];

        $powers[10]['name']    = 'nopc';
        $powers[10]['minCost'] = 0;
        $powers[10]['maxCost'] = 0;
        $powers[10]['smilies'] = ['nopc'];

        $powers[11]['name']    = 'tempmod';
        $powers[11]['minCost'] = 0;
        $powers[11]['maxCost'] = 0;
        $powers[11]['smilies'] = ['tempmod'];

        $powers[12]['name']    = 'hat';
        $powers[12]['minCost'] = 0;
        $powers[12]['maxCost'] = 0;
        $powers[12]['smilies'] = ['hat'];

        $powers[13]['name']    = 'red';
        $powers[13]['minCost'] = 0;
        $powers[13]['maxCost'] = 0;
        $powers[13]['smilies'] = ['red'];

        $powers[14]['name']    = 'green';
        $powers[14]['minCost'] = 0;
        $powers[14]['maxCost'] = 0;
        $powers[14]['smilies'] = ['green'];

        $powers[15]['name']    = 'blue';
        $powers[15]['minCost'] = 0;
        $powers[15]['maxCost'] = 0;
        $powers[15]['smilies'] = ['blue'];

        $powers[16]['name']    = 'light';
        $powers[16]['minCost'] = 0;
        $powers[16]['maxCost'] = 0;
        $powers[16]['smilies'] = ['light'];

        $powers[17]['name']    = 'heart';
        $powers[17]['minCost'] = 0;
        $powers[17]['maxCost'] = 0;
        $powers[17]['smilies'] = ['heart'];

        $powers[18]['name']    = 'shuffle';
        $powers[18]['minCost'] = 0;
        $powers[18]['maxCost'] = 0;
        $powers[18]['smilies'] = ['shuffle'];

        $powers[19]['name']    = 'animate';
        $powers[19]['minCost'] = 0;
        $powers[19]['maxCost'] = 0;
        $powers[19]['smilies'] = ['animate'];

        $powers[20]['name']    = 'square';
        $powers[20]['minCost'] = 0;
        $powers[20]['maxCost'] = 0;
        $powers[20]['smilies'] = ['square'];

        $powers[21]['name']    = 'nameglow';
        $powers[21]['minCost'] = 0;
        $powers[21]['maxCost'] = 0;
        $powers[21]['smilies'] = ['nameglow'];

        $powers[22]['name']    = 'cycle';
        $powers[22]['minCost'] = 0;
        $powers[22]['maxCost'] = 0;
        $powers[22]['smilies'] = ['cycle'];

        $powers[23]['name']    = 'hexagon';
        $powers[23]['minCost'] = 0;
        $powers[23]['maxCost'] = 0;
        $powers[23]['smilies'] = ['hexagon'];

        $powers[24]['name']    = 'clear';
        $powers[24]['minCost'] = 0;
        $powers[24]['maxCost'] = 0;
        $powers[24]['smilies'] = ['clear'];

        $powers[25]['name']    = 'boot';
        $powers[25]['minCost'] = 0;
        $powers[25]['maxCost'] = 0;
        $powers[25]['smilies'] = ['boot'];

        $powers[26]['name']    = 'octogram';
        $powers[26]['minCost'] = 0;
        $powers[26]['maxCost'] = 0;
        $powers[26]['smilies'] = ['octogram'];

        $powers[27]['name']    = 'show';
        $powers[27]['minCost'] = 0;
        $powers[27]['maxCost'] = 0;
        $powers[27]['smilies'] = ['show'];

        $powers[28]['name']    = 'superkick';
        $powers[28]['minCost'] = 0;
        $powers[28]['maxCost'] = 0;
        $powers[28]['smilies'] = ['superkick'];

        $powers[29]['name']    = 'invisible';
        $powers[29]['minCost'] = 0;
        $powers[29]['maxCost'] = 0;
        $powers[29]['smilies'] = ['invisible'];

        $powers[30]['name']    = 'pink';
        $powers[30]['minCost'] = 0;
        $powers[30]['maxCost'] = 0;
        $powers[30]['smilies'] = ['pink'];

        $powers[32]['name']    = 'guestself';
        $powers[32]['minCost'] = 0;
        $powers[32]['maxCost'] = 0;
        $powers[32]['smilies'] = ['guestself'];

        $powers[33]['name']    = 'sinbin';
        $powers[33]['minCost'] = 0;
        $powers[33]['maxCost'] = 0;
        $powers[33]['smilies'] = ['sinbin'];

        $powers[34]['name']    = 'diamond';
        $powers[34]['minCost'] = 0;
        $powers[34]['maxCost'] = 0;
        $powers[34]['smilies'] = ['diamond'];

        $powers[35]['name']    = 'purple';
        $powers[35]['minCost'] = 0;
        $powers[35]['maxCost'] = 0;
        $powers[35]['smilies'] = [
            'purple', 'putears', 'puspray', 'pusleep', 'pushim',
            'pufit', 'pueyes', 'pueyes2', 'pudizzy', 'pucry', 'pucheer'
        ];

        $powers[36]['name']    = 'ttth';
        $powers[36]['minCost'] = 0;
        $powers[36]['maxCost'] = 0;
        $powers[36]['smilies'] = ['ttth'];

        $powers[37]['name']    = 'hands';
        $powers[37]['minCost'] = 0;
        $powers[37]['maxCost'] = 0;
        $powers[37]['smilies'] = [
            'hands', 'loser', 'rockon', 'palms', 'thumbsup', 'thumbsdown', 'flip', 'shh',
            'bye', 'hug', 'clap', 'backoff', 'hehe', 'hmm', 'crazy', 'ono', 'shrug'
        ];

        $powers[38]['name']    = 'hairm';
        $powers[38]['minCost'] = 0;
        $powers[38]['maxCost'] = 0;
        $powers[38]['smilies'] = ['hairm', 'hairm2', 'hairm3', 'emo', 'nrd', 'punk'];

        $powers[39]['name']    = 'hairf';
        $powers[39]['minCost'] = 0;
        $powers[39]['maxCost'] = 0;
        $powers[39]['smilies'] = ['hairf', 'hairf2', 'hairf3', 'hairf4', 'g1', 'goth'];

        $powers[40]['name']    = 'fade';
        $powers[40]['minCost'] = 0;
        $powers[40]['maxCost'] = 0;
        $powers[40]['smilies'] = ['fade', 'noface'];

        $powers[41]['name']    = 'gag';
        $powers[41]['minCost'] = 0;
        $powers[41]['maxCost'] = 0;
        $powers[41]['smilies'] = ['gag'];

        $powers[42]['name']    = 'costumes';
        $powers[42]['minCost'] = 0;
        $powers[42]['maxCost'] = 0;
        $powers[42]['smilies'] = [
            'costumes', 'ninja', 'cb', 'police', 'nurse', 'jester', 'sphinx', 'dhat', 'crown',
            'partyhat', 'facemask', 'sherlock', 'prop', 'grad', 'dunce2', 'straw', 'ribbon'
        ];

        $powers[43]['name']    = 'six';
        $powers[43]['minCost'] = 0;
        $powers[43]['maxCost'] = 0;
        $powers[43]['smilies'] = ['six'];

        $powers[44]['name']    = 'dood';
        $powers[44]['minCost'] = 0;
        $powers[44]['maxCost'] = 0;
        $powers[44]['smilies'] = ['dood'];

        $powers[45]['name']    = 'angel';
        $powers[45]['minCost'] = 0;
        $powers[45]['maxCost'] = 0;
        $powers[45]['smilies'] = ['angel', 'halo', 'cloud', 'pray'];

        $powers[46]['name']    = 'mute';
        $powers[46]['minCost'] = 0;
        $powers[46]['maxCost'] = 0;
        $powers[46]['smilies'] = ['mute'];

        $powers[47]['name']    = 'radio';
        $powers[47]['minCost'] = 0;
        $powers[47]['maxCost'] = 0;
        $powers[47]['smilies'] = ['radio'];

        $powers[48]['name']    = 'fruit';
        $powers[48]['minCost'] = 0;
        $powers[48]['maxCost'] = 0;
        $powers[48]['smilies'] = ['fruit', 'apple', 'lemon', 'pear', 'orange', 'plum', 'banana'];

        $powers[49]['name']    = 'sport';
        $powers[49]['minCost'] = 0;
        $powers[49]['maxCost'] = 0;
        $powers[49]['smilies'] = [
            'sport', 'shirt', 'soccer', 'football', 'shirts', 'ball',
            'bb', 'foam', 'weight', 'trophy', 'helmet', 'cupw'
        ];

        $powers[50]['name']    = 'num';
        $powers[50]['minCost'] = 0;
        $powers[50]['maxCost'] = 0;
        $powers[50]['smilies'] = ['num'];

        $powers[51]['name']    = 'hush';
        $powers[51]['minCost'] = 0;
        $powers[51]['maxCost'] = 0;
        $powers[51]['smilies'] = ['hush'];

        $powers[52]['name']    = 'halloween';
        $powers[52]['minCost'] = 0;
        $powers[52]['maxCost'] = 0;
        $powers[52]['smilies'] = [
            'halloween', 'pknlaugh', 'tort', 'pkn', 'wh', 'frk', 'mmy',
            'cdn', 'grim', 'tomb', 'bat', 'ghost', 'dig', 'die'
        ];

        $powers[53]['name']    = 'anime';
        $powers[53]['minCost'] = 0;
        $powers[53]['maxCost'] = 0;
        $powers[53]['smilies'] = [
            'anime', 'hypno', 'dizzy', 'rage', 'headband', 'fan', 'comeon', 'grin', 'grump',
            'tear', 'awe', 'doh', 'pouty', 'maniac', 'squint', 'ahhh',
            'quiver', 'haira1', 'haira2', 'haira3', 'haira4', 'haira5', 'astro',
            'haira6', 'haira7', 'haira8', 'haira9', 'haira10'
        ];

        $powers[54]['name']    = 'status';
        $powers[54]['minCost'] = 0;
        $powers[54]['maxCost'] = 0;
        $powers[54]['smilies'] = ['status'];

        $powers[55]['name']    = 'thanksgiving';
        $powers[55]['minCost'] = 0;
        $powers[55]['maxCost'] = 0;
        $powers[55]['smilies'] = [
            'thanksgiving', 'pilgrimm', 'pilgrimf', 'indian', 'indian2',
            'indian3', 'chef', 'dining', 'feast2', 'eatleg'
        ];

        $powers[56]['name']    = 'snowy';
        $powers[56]['minCost'] = 0;
        $powers[56]['maxCost'] = 0;
        $powers[56]['smilies'] = ['snowy', 'snows'];

        $powers[57]['name']    = 'christmas';
        $powers[57]['minCost'] = 0;
        $powers[57]['maxCost'] = 0;
        $powers[57]['smilies'] = [
            'christmas', 'beard', 'bell', 'cane', 'ches', 'elf', 'give', 'pole',
            'sack', 'sball', 'scarf', 'sdeer', 'sfeet', 'shiver', 'sledge',
            'slist', 'smound', 'spull', 'stock', 'tree', 'wreath', 'xb1',
            'xb4', 'skiss', 'bulb', 'sgift'
        ];

        $powers[58]['name']    = 'count';
        $powers[58]['minCost'] = 0;
        $powers[58]['maxCost'] = 0;
        $powers[58]['smilies'] = ['count'];

        $powers[59]['name']    = 'stick';
        $powers[59]['minCost'] = 0;
        $powers[59]['maxCost'] = 0;
        $powers[59]['smilies'] = ['stick'];

        $powers[60]['name']    = 'dx';
        $powers[60]['minCost'] = 0;
        $powers[60]['maxCost'] = 0;
        $powers[60]['smilies'] = ['dx'];

        $powers[61]['name']    = 'tempmem';
        $powers[61]['minCost'] = 0;
        $powers[61]['maxCost'] = 0;
        $powers[61]['smilies'] = ['tempmem'];

        $powers[62]['name']    = 'valentine';
        $powers[62]['minCost'] = 0;
        $powers[62]['maxCost'] = 0;
        $powers[62]['smilies'] = [
            'valentine', 'beat', 'bheart', 'cupid', 'card', 'hug2', 'hug3',
            'ilu', 'lhand', 'ring', 'rose', 'rose2'
        ];

        $powers[64]['name']    = 'blueman';
        $powers[64]['minCost'] = 0;
        $powers[64]['maxCost'] = 0;
        $powers[64]['smilies'] = ['blueman'];

        $powers[65]['name']    = 'party';
        $powers[65]['minCost'] = 0;
        $powers[65]['maxCost'] = 0;
        $powers[65]['smilies'] = [
            'party', 'balloon', 'bride', 'cake', 'phat', 'clink', 'pdance',
            'pdance2', 'pdance3', 'groom', 'photo', 'popper', 'toast'
        ];

        $powers[66]['name']    = 'irish';
        $powers[66]['minCost'] = 0;
        $powers[66]['maxCost'] = 0;
        $powers[66]['smilies'] = [
            'irish', 'clover2', 'drink', 'igirl', 'iman', 'pot',
            'rainbow', 'shi', 'tap', 'drum', 'bagpipes'
        ];

        $powers[67]['name']    = 'flashrank';
        $powers[67]['minCost'] = 0;
        $powers[67]['maxCost'] = 0;
        $powers[67]['smilies'] = ['flashrank'];

        $powers[68]['name']    = 'easter';
        $powers[68]['minCost'] = 0;
        $powers[68]['maxCost'] = 0;
        $powers[68]['smilies'] = ['easter', 'basket', 'bunny', 'bunny2', 'chick', 'egg2', 'eggb', 'paintegg'];

        $powers[69]['name']    = 'nopm';
        $powers[69]['minCost'] = 0;
        $powers[69]['maxCost'] = 0;
        $powers[69]['smilies'] = ['nopm'];

        $powers[70]['name']    = 'banish';
        $powers[70]['minCost'] = 0;
        $powers[70]['maxCost'] = 0;
        $powers[70]['smilies'] = ['banish'];

        $powers[71]['name']    = 'circus';
        $powers[71]['minCost'] = 0;
        $powers[71]['maxCost'] = 0;
        $powers[71]['smilies'] = [
            'circus', 'acrobat', 'balloonart', 'cannon', 'clown', 'eleride', 'fireblow',
            'firewand', 'highwire', 'uniwire', 'juggler', 'lion', 'splat', 'tamer',
            'uni', 'unirope', 'wheel'
        ];

        $powers[72]['name']    = 'gkaoani';
        $powers[72]['minCost'] = 0;
        $powers[72]['maxCost'] = 0;
        $powers[72]['smilies'] = [
            'gkaoani', 'kbiggrin', 'kclap', 'kconfused', 'kcool', 'kcrying', 'kdizzy',
            'keek', 'keyerub', 'kfit', 'kfrown', 'kglare', 'khehe', 'khello', 'khug',
            'kkiss', 'klove', 'kmad', 'kmischief', 'knod', 'krant', 'kredface',
            'ksad', 'ksleepy', 'ksmile', 'ksmirk', 'kstraight', 'kstruggle',
            'ktongue', 'kun', 'kwink'
        ];

        $powers[73]['name']    = 'military';
        $powers[73]['minCost'] = 0;
        $powers[73]['maxCost'] = 0;
        $powers[73]['smilies'] = [
            'military', 'camo', 'coastguard', 'drillserg', 'gasmask',
            'm1h', 'marine', 'pilot', 'sailor', 'sailor2', 'salute', 'smoke'
        ];

        $powers[74]['name']    = 'gline';
        $powers[74]['minCost'] = 0;
        $powers[74]['maxCost'] = 0;
        $powers[74]['smilies'] = ['gline'];

        $powers[75]['name']    = 'bump';
        $powers[75]['minCost'] = 0;
        $powers[75]['maxCost'] = 0;
        $powers[75]['smilies'] = ['bump'];

        $powers[76]['name']    = 'gkaliens';
        $powers[76]['minCost'] = 0;
        $powers[76]['maxCost'] = 0;
        $powers[76]['smilies'] = [
            'gkaliens', 'kachat', 'kacrazy', 'kafang', 'kafill', 'kagab',
            'kagrin', 'kagsp', 'kalook', 'kao', 'kaoo', 'kapunch', 'kaswt',
            'katalk', 'katear', 'katears', 'kaum', 'kaupset', 'kawink'
        ];

        $powers[77]['name']    = 'scifi';
        $powers[77]['minCost'] = 0;
        $powers[77]['maxCost'] = 0;
        $powers[77]['smilies'] = [
            'scifi', 'alien2', 'alienb', 'beam', 'blob', 'borg', 'cylon',
            'jetpack', 'laser', 'vial', 'vr', 'ufo'
        ];

        $powers[78]['name']    = 'supporter';
        $powers[78]['minCost'] = 0;
        $powers[78]['maxCost'] = 0;
        $powers[78]['smilies'] = ['supporter', 'cheerleader', 'horn', 'pennant', 'corndog', 'vuvu'];

        $powers[79]['name']    = 'tempown';
        $powers[79]['minCost'] = 0;
        $powers[79]['maxCost'] = 0;
        $powers[79]['smilies'] = ['tempown'];

        $powers[80]['name']    = 'gcontrol';
        $powers[80]['minCost'] = 0;
        $powers[80]['maxCost'] = 0;
        $powers[80]['smilies'] = ['gcontrol'];

        $powers[81]['name']    = 'tickle';
        $powers[81]['minCost'] = 0;
        $powers[81]['maxCost'] = 0;
        $powers[81]['smilies'] = ['tickle'];

        $powers[82]['name']    = 'sea';
        $powers[82]['minCost'] = 0;
        $powers[82]['maxCost'] = 0;
        $powers[82]['smilies'] = [
            'sea', 'fish', 'fish2', 'fish3', 'bubbles', 'crab', 'diver',
            'dolphin', 'jellyfish', 'octopus', 'shrimp', 'starfish', 'turtle', 'weed'
        ];

        $powers[83]['name']    = 'silly';
        $powers[83]['minCost'] = 0;
        $powers[83]['maxCost'] = 0;
        $powers[83]['smilies'] = [
            'silly', 'bonk', 'burp', 'crazy2', 'dopey', 'impact', 'irked', 'itchy',
            'mischief2', 'nose', 'raspberry', 'rolleye', 'spit', 'string'
        ];

        $powers[84]['name']    = 'blastpro';
        $powers[84]['minCost'] = 0;
        $powers[84]['maxCost'] = 0;
        $powers[84]['smilies'] = ['blastpro'];

        $powers[85]['name']    = 'flag';
        $powers[85]['minCost'] = 0;
        $powers[85]['maxCost'] = 0;
        $powers[85]['smilies'] = ['flag'];

        $powers[86]['name']    = 'blastban';
        $powers[86]['minCost'] = 0;
        $powers[86]['maxCost'] = 0;
        $powers[86]['smilies'] = ['blastban'];

        $powers[87]['name']    = 'independence';
        $powers[87]['minCost'] = 0;
        $powers[87]['maxCost'] = 0;
        $powers[87]['smilies'] = [
            'independence', 'abe', 'bbq', 'flagwave', 'fwlaunch', 'glowstick',
            'liberty', 'sparkler', 'starbounce', 'starburst', 'starring',
            'tiphat', 'usface', 'usss'
        ];

        $powers[88]['name']    = 'blastde';
        $powers[88]['minCost'] = 0;
        $powers[88]['maxCost'] = 0;
        $powers[88]['smilies'] = ['blastde'];

        $powers[89]['name']    = 'summer';
        $powers[89]['minCost'] = 0;
        $powers[89]['maxCost'] = 0;
        $powers[89]['smilies'] = [
            'summer', 'beachbbq', 'cooler', 'efan', 'fishing', 'frisbee', 'goggles',
            'kayak', 'laytowl', 'pina', 'sanddig', 'sandplay', 'bands',
            'towl', 'waterbottle', 'waterskii'
        ];

        $powers[90]['name']    = 'bad';
        $powers[90]['minCost'] = 0;
        $powers[90]['maxCost'] = 0;
        $powers[90]['smilies'] = ['bad', 'biker', 'slingshot', 'thief', 'fbomb', 'liar'];

        $powers[91]['name']    = 'rapid';
        $powers[91]['minCost'] = 0;
        $powers[91]['maxCost'] = 0;
        $powers[91]['smilies'] = ['rapid'];

        $powers[92]['name']    = 'horror';
        $powers[92]['minCost'] = 0;
        $powers[92]['maxCost'] = 0;
        $powers[92]['smilies'] = [
            'horror', 'mist', 'franken', 'goblin', 'knife', 'pkncut',
            'spider', 'triclops', 'vamp', 'warewolf'
        ];

        $powers[93]['name']    = 'mint';
        $powers[93]['minCost'] = 0;
        $powers[93]['maxCost'] = 0;
        $powers[93]['smilies'] = ['mint', 'hit', 'dodge', 'lob', 'mobounce', 'moring'];

        $powers[94]['name']    = 'blastkick';
        $powers[94]['minCost'] = 0;
        $powers[94]['maxCost'] = 0;
        $powers[94]['smilies'] = ['blastkick'];

        $powers[95]['name']    = 'everypower';
        $powers[95]['minCost'] = 0;
        $powers[95]['maxCost'] = 0;
        $powers[95]['smilies'] = ['everypower'];

        $powers[96]['name']    = 'winter';
        $powers[96]['minCost'] = 0;
        $powers[96]['maxCost'] = 0;
        $powers[96]['smilies'] = [
            'winter', 'carolers', 'decortree', 'giftdrop', 'giftrattle', 'gingerbread',
            'pullsled', 'antler', 'shovel', 'accident', 'snowboard', 'snowfight',
            'snowm', 'xface', 'snowmobile', 'ornament', 'earmuffs', 'snowglobe',
            'cold', 'freezing', 'snowover', 'snowangel', 'windy', 'icecube'
        ];

        $powers[97]['name']    = 'adventure';
        $powers[97]['minCost'] = 0;
        $powers[97]['maxCost'] = 0;
        $powers[97]['smilies'] = [
            'adventure', 'campfire', 'canopy', 'compass', 'firstaid', 'hangglider',
            'hunting', 'lantern', 'map', 'marshmallow', 'iceaxe', 'swing',
            'waterskiing', 'zipline', 'smokes'
        ];

        $powers[98]['name']    = 'feast';
        $powers[98]['minCost'] = 0;
        $powers[98]['maxCost'] = 0;
        $powers[98]['smilies'] = [
            'feast', 'bowleat', 'burger', 'burgerlook', 'candycorn', 'carrot', 'cherry',
            'chicken', 'chili', 'chipeat', 'coffeesplash', 'donut', 'eatspagetti',
            'eattakeout', 'eggcook', 'eggplant', 'fortunecookie', 'fries', 'icecream',
            'icecreameat', 'peanut', 'popcorn', 'pretzel', 'soupeat', 'spam', 'stirpan',
            'takeout', 'thinkfood', 'toast2', 'pizza'
        ];

        $powers[99]['name']    = 'single';
        $powers[99]['minCost'] = 0;
        $powers[99]['maxCost'] = 0;
        $powers[99]['smilies'] = ['single', 'asif', 'one', 'heartburst', 'heartbounce', 'noway'];

        $powers[100]['name']    = 'link';
        $powers[100]['minCost'] = 0;
        $powers[100]['maxCost'] = 0;
        $powers[100]['smilies'] = ['link'];

        $powers[101]['name']    = 'shocker';
        $powers[101]['minCost'] = 0;
        $powers[101]['maxCost'] = 0;
        $powers[101]['smilies'] = [
            'shocker', 'duck', 'electrocute', 'eyepop', 'flamed', 'lookaround',
            'scream', 'wow', 'dazed', 'hazey', 'drool', 'footmouth', 'mindblow',
            'misspeak', 'relieved', 'uvula', 'arc'
        ];

        $powers[102]['name']    = 'fairy';
        $powers[102]['minCost'] = 0;
        $powers[102]['maxCost'] = 0;
        $powers[102]['smilies'] = [
            'fairy', 'butterfly', 'fcrown', 'fhair', 'fhat', 'flower2', 'frog',
            'fwings', 'heartwand', 'mushrooms', 'shards'
        ];

        $powers[103]['name']    = 'namecolor';
        $powers[103]['minCost'] = 0;
        $powers[103]['maxCost'] = 0;
        $powers[103]['smilies'] = ['namecolor'];

        $powers[104]['name']    = 'gkbear';
        $powers[104]['minCost'] = 0;
        $powers[104]['maxCost'] = 0;
        $powers[104]['smilies'] = [
            'gkbear', 'kbawe', 'kbcold', 'kbcry', 'kbdance', 'kbpunch', 'kbsad',
            'kbscream', 'kbweep', 'kbwink', 'kbyawn'
        ];

        $powers[105]['name']    = 'angry';
        $powers[105]['minCost'] = 0;
        $powers[105]['maxCost'] = 0;
        $powers[105]['smilies'] = [
            'angry', 'explode', 'facepalm', 'bite', 'hissyfit', 'mutter',
            'potstir', 'pullhair', 'raging', 'ticked', 'yell',
            'flame', 'grumpy2', 'headache', 'timebomb'
        ];

        $powers[106]['name']    = 'gscol';
        $powers[106]['minCost'] = 0;
        $powers[106]['maxCost'] = 0;
        $powers[106]['smilies'] = ['gscol'];

        $powers[107]['name']    = 'ugly';
        $powers[107]['minCost'] = 0;
        $powers[107]['maxCost'] = 0;
        $powers[107]['smilies'] = [
            'ugly', 'uangel', 'udemon', 'uevil', 'uhappy', 'ulook', 'uthink',
            'usad', 'uscratch', 'usmile', 'utongue', 'uwink'
        ];

        $powers[108]['name']    = 'love';
        $powers[108]['minCost'] = 0;
        $powers[108]['maxCost'] = 0;
        $powers[108]['smilies'] = [
            'love', 'coy', 'flirt', 'freehugs', 'heartbeat', 'heartblow',
            'ihu', 'lovedraw', 'ucute', 'uhot'
        ];

        $powers[109]['name']    = 'barge';
        $powers[109]['minCost'] = 0;
        $powers[109]['maxCost'] = 0;
        $powers[109]['smilies'] = ['barge'];

        $powers[110]['name']    = 'gkkitty';
        $powers[110]['minCost'] = 0;
        $powers[110]['maxCost'] = 0;
        $powers[110]['smilies'] = [
            'gkkitty', 'kkbiggrin', 'kkconfused', 'kkcool', 'kkcrying', 'kkd', 'kkdance',
            'kkdead', 'kkeek', 'kkfrown', 'kkhide', 'kkhug', 'kkmad', 'kknme',
            'kkpaws1', 'kkpaws2', 'kkpaws3', 'kkredface', 'kkscn', 'kkshock',
            'kksleepy', 'kksmile', 'kkstraightface', 'kkstruggle', 'kktongue',
            'kkun', 'kkwary', 'kkwink', 'kkx3', 'kkxd', 'kkyawn'
        ];

        $powers[111]['name']    = 'fantasy';
        $powers[111]['minCost'] = 0;
        $powers[111]['maxCost'] = 0;
        $powers[111]['smilies'] = [
            'fantasy', 'axe', 'darkeyes', 'dragon', 'flail', 'hole', 'helmet2',
            'medusa', 'orcm', 'elf2', 'ogre', 'orcf', 'invis', 'scroll',
            'slash', 'smskull', 'sword', 'wizard'
        ];

        $powers[112]['name']    = 'announce';
        $powers[112]['minCost'] = 0;
        $powers[112]['maxCost'] = 0;
        $powers[112]['smilies'] = ['announce'];

        $powers[113]['name']    = 'hero';
        $powers[113]['minCost'] = 0;
        $powers[113]['maxCost'] = 0;
        $powers[113]['smilies'] = [
            'hero', 'daggers', 'electro', 'epi', 'fireball', 'flying', 'genie',
            'iceman', 'invisi', 'jekyl', 'maddr', 'telekin', 'amazon', 'upaway', 'ripshirt'
        ];

        $powers[114]['name']    = 'rankpool';
        $powers[114]['minCost'] = 0;
        $powers[114]['maxCost'] = 0;
        $powers[114]['smilies'] = ['rankpool'];

        $powers[115]['name']    = 'spin';
        $powers[115]['minCost'] = 0;
        $powers[115]['maxCost'] = 0;
        $powers[115]['smilies'] = ['spin'];

        $powers[116]['name']    = 'animal';
        $powers[116]['minCost'] = 0;
        $powers[116]['maxCost'] = 0;
        $powers[116]['smilies'] = [
            'animal', 'doggy', 'giraffe', 'goat', 'lion2', 'monkey', 'mouse',
            'panda2', 'raccoon', 'sheep', 'unicorn'
        ];

        $powers[117]['name']    = 'music';
        $powers[117]['minCost'] = 0;
        $powers[117]['maxCost'] = 0;
        $powers[117]['smilies'] = [
            'music', 'disco', 'dj', 'drums', 'flute', 'guitar', 'harp',
            'piano', 'trumpet', 'violin', 'zippo'
        ];

        $powers[118]['name']    = 'gkpanda';
        $powers[118]['minCost'] = 0;
        $powers[118]['maxCost'] = 0;
        $powers[118]['smilies'] = [
            'gkpanda', 'kpfit', 'kpembarassed', 'kppaws', 'kpd', 'kpannoyed',
            'kppaws2', 'kplove', 'kpjoy', 'kpcrying', 'kphurt'
        ];

        $powers[119]['name']    = 'unwell';
        $powers[119]['minCost'] = 0;
        $powers[119]['maxCost'] = 0;
        $powers[119]['smilies'] = [
            'unwell', 'blownose', 'chill', 'cough', 'icepack', 'sickface',
            'sneeze', 'soup', 'thermometer', 'crutches', 'feint',
            'cough2', 'headknock', 'crutches', 'sweats'
        ];

        $powers[120]['name']    = 'events';
        $powers[120]['minCost'] = 0;
        $powers[120]['maxCost'] = 0;
        $powers[120]['smilies'] = ['events'];

        $powers[121]['name']    = 'zap';
        $powers[121]['minCost'] = 0;
        $powers[121]['maxCost'] = 0;
        $powers[121]['smilies'] = ['zap', 'cringe', 'shake', 'shocking'];

        $powers[122]['name']    = 'sins';
        $powers[122]['minCost'] = 0;
        $powers[122]['maxCost'] = 0;
        $powers[122]['smilies'] = ['sins', 'envy', 'gluttony', 'greed', 'lust', 'pride', 'sloth', 'wrath'];

        $powers[123]['name']    = 'outfit';
        $powers[123]['minCost'] = 0;
        $powers[123]['maxCost'] = 0;
        $powers[123]['smilies'] = [
            'outfit', 'whip', 'turban', 'tophat', 'tinfoil', 'sombrero',
            'paperbag', 'paperbag2', 'grandpa', 'grandma', 'glassesslip',
            'disguise', 'curlers', 'braces', 'blindfold', 'beret'
        ];

        $powers[124]['name']    = 'wildwest';
        $powers[124]['minCost'] = 0;
        $powers[124]['maxCost'] = 0;
        $powers[124]['smilies'] = [
            'wildwest', 'undertaker', 'sheriff', 'ropeg', 'ropeb',
            'push', 'prospector', 'outlaw', 'native', 'bullride', 'bartender'
        ];

        $powers[125]['name']    = 'work';
        $powers[125]['minCost'] = 0;
        $powers[125]['maxCost'] = 0;
        $powers[125]['smilies'] = [
            'work', 'officeworker', 'driver', 'doctor', 'chef2', 'burgerflipper',
            'blacksmith', 'astronaut', 'teacher', 'stewardess', 'shepherd',
            'scientist', 'plumber', 'paperboy', 'miner', 'mechanic',
            'judge', 'journalist', 'gardener', 'fortuneteller', 'fireman'
        ];

        $powers[126]['name']    = 'banpool';
        $powers[126]['minCost'] = 0;
        $powers[126]['maxCost'] = 0;
        $powers[126]['smilies'] = ['banpool'];

        $powers[128]['name']    = 'beach';
        $powers[128]['minCost'] = 0;
        $powers[128]['maxCost'] = 0;
        $powers[128]['smilies'] = [
            'beach', 'icman', 'lifeguard', 'lotion', 'metaldetect',
            'sandbury', 'sandfun', 'shkhat', 'sunburn', 'surf', 'tanning'
        ];

        $powers[129]['name']    = 'candy';
        $powers[129]['minCost'] = 0;
        $powers[129]['maxCost'] = 0;
        $powers[129]['smilies'] = [
            'candy', 'candy', 'cdycorn', 'cdyback', 'donuteat', 'eatchoco',
            'floss', 'gum', 'lolipop', 'mm'
        ];

        $powers[130]['name']    = 'gback';
        $powers[130]['minCost'] = 0;
        $powers[130]['maxCost'] = 0;
        $powers[130]['smilies'] = ['gback'];

        $powers[131]['name']    = 'zodiac';
        $powers[131]['minCost'] = 0;
        $powers[131]['maxCost'] = 0;
        $powers[131]['smilies'] = [
            'zodiac', 'aries', 'taurus', 'gemini', 'cancer', 'leo',
            'virgo', 'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces'
        ];

        $powers[132]['name']    = 'flower';
        $powers[132]['minCost'] = 0;
        $powers[132]['maxCost'] = 0;
        $powers[132]['smilies'] = [
            'flower', 'fangry', 'fcry', 'fcool', 'feek', 'fconfused', 'ftongue',
            'fredface', 'fsleepy', 'fbiggrin', 'fsad', 'fwink'
        ];

        $powers[133]['name']    = 'space';
        $powers[133]['minCost'] = 0;
        $powers[133]['maxCost'] = 0;
        $powers[133]['smilies'] = [
            'space', 'eclipse', 'meteorite', 'nasa', 'radiotele', 'rocket',
            'satellite', 'saucer', 'shuttle', 'space', 'telescope'
        ];

        $powers[134]['name']    = 'snakeban';
        $powers[134]['minCost'] = 0;
        $powers[134]['maxCost'] = 0;
        $powers[134]['smilies'] = [
            'snakeban', 'sncool', 'sneek', 'snredface', 'snconfused',
            'snfrown', 'snbiggrin', 'snangry', 'snsleepy', 'sncry', 'snsmile', 'snwink'
        ];

        $powers[135]['name']    = 'stoneage';
        $powers[135]['minCost'] = 0;
        $powers[135]['maxCost'] = 0;
        $powers[135]['smilies'] = [
            'stoneage', 'caveman', 'dino', 'cavewoman', 'cavework', 'cavebeard',
            'caveclothes', 'torch', 'spear', 'rocksmash', 'cavehair', 'boneswing',
            'wallart', 'stickfire', 'cavebeard2'
        ];

        $powers[136]['name']    = 'spaceban';
        $powers[136]['minCost'] = 0;
        $powers[136]['maxCost'] = 0;
        $powers[136]['smilies'] = ['spaceban', 'moonb'];

        $powers[137]['name']    = 'dance';
        $powers[137]['minCost'] = 0;
        $powers[137]['maxCost'] = 0;
        $powers[137]['smilies'] = [
            'dance', 'dance1', 'dance2', 'dance3', 'dance4', 'dance5',
            'dance6', 'dance7', 'dance8', 'dance9', 'dance10', 'dance11'
        ];

        $powers[138]['name']    = 'kpeng';
        $powers[138]['minCost'] = 0;
        $powers[138]['maxCost'] = 0;
        $powers[138]['smilies'] = [
            'kpeng', 'kpesneeze', 'kpeshy', 'kpeshame', 'kpejoy',
            'kpeglare', 'kpefit', 'kpedots', 'kpedizzy', 'kpeclap',
            'kpeangry', 'kpedance', 'kpepop'
        ];

        $powers[139]['name']    = 'nerd';
        $powers[139]['minCost'] = 0;
        $powers[139]['maxCost'] = 0;
        $powers[139]['smilies'] = [
            'nerd', 'aplus', 'bowtie', 'brain', 'coder', 'gates',
            'nglasses', 'nsci', 'phone', 'read', 'calc'
        ];

        $powers[140]['name']    = 'matchban';
        $powers[140]['minCost'] = 0;
        $powers[140]['maxCost'] = 0;
        $powers[140]['smilies'] = ['matchban'];

        $powers[141]['name']    = 'school';
        $powers[141]['minCost'] = 0;
        $powers[141]['maxCost'] = 0;
        $powers[141]['smilies'] = [
            'school', 'backpack', 'daycare', 'gts', 'punished',
            'rubber', 'schoolgirl', 'sbell', 'studying', 'tabsc', 'teacher2', 'writing'
        ];

        $powers[142]['name']    = 'silentm';
        $powers[142]['minCost'] = 0;
        $powers[142]['maxCost'] = 0;
        $powers[142]['smilies'] = ['silentm'];

        $powers[143]['name']    = 'punch';
        $powers[143]['minCost'] = 0;
        $powers[143]['maxCost'] = 0;
        $powers[143]['smilies'] = [
            'punch', 'pconfused', 'pcrying', 'pcute', 'pdead', 'pfury',
            'pgiggle', 'pglare', 'pjump', 'pmanic', 'punched',
            'pshades', 'pshy', 'psleepy', 'pthink'
        ];

        $powers[144]['name']    = 'away';
        $powers[144]['minCost'] = 0;
        $powers[144]['maxCost'] = 0;
        $powers[144]['smilies'] = ['away'];

        $powers[145]['name']    = 'peace';
        $powers[145]['minCost'] = 0;
        $powers[145]['maxCost'] = 0;
        $powers[145]['smilies'] = [
            'peace', 'dove', 'dove2', 'dove3', 'hglass', 'phair1',
            'phair2', 'phair3', 'rbe', 'reggae'
        ];

        $powers[146]['name']    = 'kchick';
        $powers[146]['minCost'] = 0;
        $powers[146]['maxCost'] = 0;
        $powers[146]['smilies'] = [
            'kchick', 'kccrying', 'kcdizzy', 'kceek', 'kcglare',
            'kclook', 'kcsad', 'kcsmug', 'kctongue', 'kcwink'
        ];

        $powers[147]['name']    = 'carve';
        $powers[147]['minCost'] = 0;
        $powers[147]['maxCost'] = 0;
        $powers[147]['smilies'] = [
            'carve', 'carveduh', 'carvegrin', 'carvescream', 'carvesly',
            'carvesmile', 'dracula', 'grim2', 'pknhide', 'vampire'
        ];

        $powers[148]['name']    = 'spooky';
        $powers[148]['minCost'] = 0;
        $powers[148]['maxCost'] = 0;
        $powers[148]['smilies'] = [
            'spooky', 'sixeyes', 'bat2', 'blackcat', 'blackwidow',
            'ghost2', 'hockeymask', 'plant', 'bloodyknife', 'scarecrow'
        ];

        $powers[149]['name']    = 'kdog';
        $powers[149]['minCost'] = 0;
        $powers[149]['maxCost'] = 0;
        $powers[149]['smilies'] = [
            'kdog', 'kdcheer', 'kdcrazy', 'kddead', 'kdfit', 'kdglare',
            'kdsad', 'kdshocked', 'kdsleepy', 'kdsmile', 'kdtired', 'kdwoo'
        ];

        $powers[150]['name']    = 'bot';
        $powers[150]['minCost'] = 0;
        $powers[150]['maxCost'] = 0;
        $powers[150]['smilies'] = ['bot', 'bot2', 'bot3', 'bot4', 'bot5', 'bot6', 'bot7', 'bot8', 'bot9'];

        $powers[151]['name']    = 'manga';
        $powers[151]['minCost'] = 0;
        $powers[151]['maxCost'] = 0;
        $powers[151]['smilies'] = [
            'manga', 'manga1', 'manga2', 'manga3', 'manga4', 'manga5', 'manga6',
            'manga7', 'manga8', 'manga9', 'manga10', 'manga11', 'manga12',
            'manga13', 'manga14', 'manga15', 'manga16', 'manga17'
        ];

        $powers[152]['name']    = 'mazeban';
        $powers[152]['minCost'] = 0;
        $powers[152]['maxCost'] = 0;
        $powers[152]['smilies'] = ['mazeban'];

        $powers[153]['name']    = 'gold';
        $powers[153]['minCost'] = 0;
        $powers[153]['maxCost'] = 0;
        $powers[153]['smilies'] = ['gold', 'goldb', 'bars', 'bar', 'goldstar'];

        $powers[154]['name']    = 'snowman';
        $powers[154]['minCost'] = 0;
        $powers[154]['maxCost'] = 0;
        $powers[154]['smilies'] = [
            'snowman', 'smcry', 'smgrin', 'smlaugh', 'smredface', 'smsad',
            'smshocked', 'smsleepy', 'smtongue', 'smmad', 'smcool'
        ];

        $powers[155]['name']    = 'reindeer';
        $powers[155]['minCost'] = 0;
        $powers[155]['maxCost'] = 0;
        $powers[155]['smilies'] = [
            'reindeer', 'resurprised', 'resmirk', 'resleepy', 'resad',
            'reredface', 'relaugh', 'reglare', 'recry',
            'recool', 'reangry', 'renose', 'reback'
        ];

        $powers[156]['name']    = 'santa';
        $powers[156]['minCost'] = 0;
        $powers[156]['maxCost'] = 0;
        $powers[156]['smilies'] = [
            'santa', 'sabiggrin', 'saconfused', 'sacry', 'saglare',
            'saredface', 'sasad', 'sastraightface',
            'sasurprised', 'satongue', 'sawink'
        ];

        $powers[157]['name']    = 'sparta';
        $powers[157]['minCost'] = 0;
        $powers[157]['maxCost'] = 0;
        $powers[157]['smilies'] = [
            'sparta', 'spartayell', 'getready', 'helmet3', 'soldier',
            'spants', 'spartafight', 'spartan', 'spartan2', 'truewar', 'xerxes'
        ];

        $powers[158]['name']    = 'dunce';
        $powers[158]['minCost'] = 0;
        $powers[158]['maxCost'] = 0;
        $powers[158]['smilies'] = ['dunce', 'duh', 'doh2'];

        $powers[160]['name']    = 'newyear';
        $powers[160]['minCost'] = 0;
        $powers[160]['maxCost'] = 0;
        $powers[160]['smilies'] = [
            'newyear', 'calendar', 'celebration', 'champagne2', 'champagneback',
            'clink2', 'firework1', 'firework2', 'nyball', 'nyhat',
            'nykiss', 'nyparty', 'sparkler2'
        ];

        $powers[161]['name']    = 'can';
        $powers[161]['minCost'] = 0;
        $powers[161]['maxCost'] = 0;
        $powers[161]['smilies'] = [
            'can', 'canangel', 'canbounce', 'cancontempt', 'canfury',
            'canoo', 'canshifty', 'canthink', 'cantwitch', 'canum', 'canun'
        ];

        $powers[162]['name']    = 'codeban';
        $powers[162]['minCost'] = 0;
        $powers[162]['maxCost'] = 0;
        $powers[162]['smilies'] = ['codeban'];

        $powers[163]['name']    = 'magicfx';
        $powers[163]['minCost'] = 0;
        $powers[163]['maxCost'] = 0;
        $powers[163]['smilies'] = ['magicfx'];

        $powers[164]['name']    = 'spy';
        $powers[164]['minCost'] = 0;
        $powers[164]['maxCost'] = 0;
        $powers[164]['smilies'] = [
            'spy', 'crosshair', 'folder', 'footprints', 'headset',
            'keyhole', 'keypad', 'peeking', 'radar', 'spydrink',
            'spyeye', 'spying', 'spypaper', 'spyrope'
        ];

        $powers[165]['name']    = 'kduck';
        $powers[165]['minCost'] = 0;
        $powers[165]['maxCost'] = 0;
        $powers[165]['smilies'] = [
            'kduck', 'kduckback', 'kduck1', 'kduck2', 'kduck3',
            'kduck4', 'kduck5', 'kduck6', 'kduck7', 'kduck8',
            'kduck9', 'kduck10', 'kduck11', 'kduck12'
        ];

        $powers[166]['name']    = 'heartfx';
        $powers[166]['minCost'] = 0;
        $powers[166]['maxCost'] = 0;
        $powers[166]['smilies'] = ['heartfx', 'heartfx', 'heartfx2', 'heartfx3'];

        $powers[167]['name']    = 'carnival';
        $powers[167]['minCost'] = 0;
        $powers[167]['maxCost'] = 0;
        $powers[167]['smilies'] = [
            'carnival', 'anonmask', 'beads', 'brazilboom', 'carniphant',
            'cjester', 'cmask', 'flowersquirt', 'headdress', 'kreu',
            'shakeit', 'wannasamba', 'yeayea', 'cjester2', 'cbird', 'cangel'
        ];

        $powers[168]['name']    = 'topspin';
        $powers[168]['minCost'] = 0;
        $powers[168]['maxCost'] = 0;
        $powers[168]['smilies'] = ['topspin'];

        $powers[169]['name']    = 'movie';
        $powers[169]['minCost'] = 0;
        $powers[169]['maxCost'] = 0;
        $powers[169]['smilies'] = [
            'movie', 'action', 'admission', 'booth', 'cameraman',
            'director', 'drink2', 'film', 'filmroll', 'popcorn', 'pose', 'tickets'
        ];

        $powers[170]['name']    = 'monster';
        $powers[170]['minCost'] = 0;
        $powers[170]['maxCost'] = 0;
        $powers[170]['smilies'] = ['monster', 'mangel', 'mbat', 'mbear', 'mchick', 'mdrip', 'mfish', 'mglare'];

        $powers[171]['name']    = 'kat';
        $powers[171]['minCost'] = 0;
        $powers[171]['maxCost'] = 0;
        $powers[171]['smilies'] = [
            'kat', 'kat1', 'kat2', 'kat3', 'kat4', 'kat5', 'kat6',
            'kat7', 'kat8', 'kat9', 'kat10', 'katback'
        ];

        $powers[172]['name']    = 'typing';
        $powers[172]['minCost'] = 0;
        $powers[172]['maxCost'] = 0;
        $powers[172]['smilies'] = ['typing', 'typing1'];

        $powers[173]['name']    = 'ksheep';
        $powers[173]['minCost'] = 0;
        $powers[173]['maxCost'] = 0;
        $powers[173]['smilies'] = [
            'ksheep', 'ksheep1', 'ksheep2', 'ksheep3', 'ksheep4', 'ksheep5',
            'ksheep6', 'ksheep7', 'ksheep8', 'ksheep9', 'ksheep10', 'ksheepback'
        ];

        $powers[174]['name']    = 'pulsefx';
        $powers[174]['minCost'] = 0;
        $powers[174]['maxCost'] = 0;
        $powers[174]['smilies'] = ['pulsefx', 'pulsefxback'];

        $powers[175]['name']    = 'blobby';
        $powers[175]['minCost'] = 0;
        $powers[175]['maxCost'] = 0;
        $powers[175]['smilies'] = [
            'blobby', 'bbclap', 'bbconfused', 'bbcry', 'bbfit', 'bbglare',
            'bbhug', 'bblaugh', 'bboops', 'bbpout', 'bbwink', 'bbback'
        ];

        $powers[176]['name']    = 'reverse';
        $powers[176]['minCost'] = 0;
        $powers[176]['maxCost'] = 0;
        $powers[176]['smilies'] = ['reverse'];

        $powers[177]['name']    = 'fuzzy';
        $powers[177]['minCost'] = 0;
        $powers[177]['maxCost'] = 0;
        $powers[177]['smilies'] = [
            'fuzzy', 'fzangel', 'fzback', 'fzbiggrin', 'fzcool', 'fzcrazy',
            'fzd', 'fzdance', 'fzsad', 'fzstretch', 'fztongue', 'fztwirl'
        ];

        $powers[178]['name']    = 'spiralfx';
        $powers[178]['minCost'] = 0;
        $powers[178]['maxCost'] = 0;
        $powers[178]['smilies'] = ['spiralfx', 'spiralfx2'];

        $powers[179]['name']    = 'nursing';
        $powers[179]['minCost'] = 0;
        $powers[179]['maxCost'] = 0;
        $powers[179]['smilies'] = [
            'nursing', 'crazyn', 'nblood', 'nbroken', 'ndoc', 'nlist',
            'nmask', 'nne', 'noxy', 'nsurg'
        ];

        $powers[180]['name']    = 'gsound';
        $powers[180]['minCost'] = 0;
        $powers[180]['maxCost'] = 0;
        $powers[180]['smilies'] = ['gsound', 'megap', 'volume', 'tooloud', 'canthear', 'playbtn'];

        $powers[181]['name']    = 'kbee';
        $powers[181]['minCost'] = 0;
        $powers[181]['maxCost'] = 0;
        $powers[181]['smilies'] = [
            'kbee', 'kbeeback', 'kbeeclap', 'kbeecry', 'kbeed', 'kbeedance',
            'kbeefedup', 'kbeejoy', 'kbeemad', 'kbeepunch', 'kbeexx', 'kbeeyay'
        ];

        $powers[182]['name']    = 'vortexfx';
        $powers[182]['minCost'] = 0;
        $powers[182]['maxCost'] = 0;
        $powers[182]['smilies'] = ['vortexfx', 'vortexfx2'];

        $powers[183]['name']    = 'jail';
        $powers[183]['minCost'] = 0;
        $powers[183]['maxCost'] = 0;
        $powers[183]['smilies'] = [
            'jail', 'ballchain', 'bobby', 'copstop', 'cracker', 'cuffs',
            'gavel', 'handsair', 'jailnumber', 'mugshot', 'prisoner'
        ];

        $powers[184]['name']    = 'zip';
        $powers[184]['minCost'] = 0;
        $powers[184]['maxCost'] = 0;
        $powers[184]['smilies'] = ['zip'];

        $powers[185]['name']    = 'drip';
        $powers[185]['minCost'] = 0;
        $powers[185]['maxCost'] = 0;
        $powers[185]['smilies'] = [
            'drip', 'dripclap', 'dripdance', 'dripeek', 'dripfit', 'dripglare',
            'driphehe', 'driphello', 'driphug', 'driplaugh', 'dripsad',
            'dripshocked', 'driptired', 'dripxd', 'dripyell'
        ];

        $powers[186]['name']    = 'moustache';
        $powers[186]['minCost'] = 0;
        $powers[186]['maxCost'] = 0;
        $powers[186]['smilies'] = [
            'moustache', 'moustache1', 'moustache2', 'moustache3',
            'moustache4', 'moustache5', 'moustache6',
            'moustache7', 'moustache8', 'moustache9'
        ];

        $powers[187]['name']    = 'whirlfx';
        $powers[187]['minCost'] = 0;
        $powers[187]['maxCost'] = 0;
        $powers[187]['smilies'] = ['whirlfx'];

        $powers[188]['name']    = 'doodlerace';
        $powers[188]['minCost'] = 0;
        $powers[188]['maxCost'] = 0;
        $powers[188]['smilies'] = ['doodlerace'];

        $powers[189]['name']    = 'olympic';
        $powers[189]['minCost'] = 0;
        $powers[189]['maxCost'] = 0;
        $powers[189]['smilies'] = [
            'olympic', 'bronzem', 'goldm', 'oboxing', 'ocycling', 'odiving',
            'ogymnastics', 'ohurdles', 'orowing', 'orunning', 'oswimming',
            'otennis', 'otorch', 'ovolleyball', 'silverm',
            'oarchery', 'obasketball', 'ofencing', 'ohockey'
        ];

        $powers[190]['name']    = 'aliens';
        $powers[190]['minCost'] = 0;
        $powers[190]['maxCost'] = 0;
        $powers[190]['smilies'] = [
            'aliens', 'aliens', 'alilaugh', 'alidead', 'aliclap', 'alid',
            'alilove', 'aliscratch', 'alitalk', 'alicry', 'alitongue', 'aliyay', 'aliback'
        ];

        $powers[192]['name']    = 'matchrace';
        $powers[192]['minCost'] = 0;
        $powers[192]['maxCost'] = 0;
        $powers[192]['smilies'] = ['matchrace'];

        $powers[193]['name']    = 'burningheart';
        $powers[193]['minCost'] = 0;
        $powers[193]['maxCost'] = 0;
        $powers[193]['smilies'] = ['burningheart', 'bheartb'];

        $powers[194]['name']    = 'snakerace';
        $powers[194]['minCost'] = 0;
        $powers[194]['maxCost'] = 0;
        $powers[194]['smilies'] = ['snakerace'];

        $powers[195]['name']    = 'kpig';
        $powers[195]['minCost'] = 0;
        $powers[195]['maxCost'] = 0;
        $powers[195]['smilies'] = [
            'kpig', 'kpigangry', 'kpigback', 'kpigball', 'kpigfraz', 'kpiglove',
            'kpigmad', 'kpigmud', 'kpigpals', 'kpigsleep', 'kpigsnoot', 'kpigwrite'
        ];

        $powers[196]['name']    = 'poker';
        $powers[196]['minCost'] = 0;
        $powers[196]['maxCost'] = 0;
        $powers[196]['smilies'] = [
            'poker', 'pclubs', 'pobluff', 'pochips', 'pod', 'pogirl', 'poplayer',
            'povip', 'powin', 'poyay', 'pspades', 'pdiamonds', 'phearts'
        ];

        $powers[197]['name']    = 'pony';
        $powers[197]['minCost'] = 0;
        $powers[197]['maxCost'] = 0;
        $powers[197]['smilies'] = [
            'pony', 'poback', 'pocall', 'poclap', 'pocry', 'poeat', 'pofan', 'poglasses',
            'pohay', 'pomane', 'ponyd', 'powhip'
        ];

        $powers[198]['name']    = 'clockfx';
        $powers[198]['minCost'] = 0;
        $powers[198]['maxCost'] = 0;
        $powers[198]['smilies'] = ['clockfx', 'clockfx2', 'clockfx3', 'clockfx4'];

        $powers[199]['name']    = 'drop';
        $powers[199]['minCost'] = 0;
        $powers[199]['maxCost'] = 0;
        $powers[199]['smilies'] = [
            'drop', 'dropworry', 'dropumb', 'dropscratch', 'droprub', 'drophey',
            'dropeat', 'dropdance', 'dropclap', 'dropback'
        ];

        $powers[200]['name']    = 'spacewar';
        $powers[200]['minCost'] = 0;
        $powers[200]['maxCost'] = 0;
        $powers[200]['smilies'] = ['spacewar'];

        $powers[201]['name']    = 'speech';
        $powers[201]['minCost'] = 0;
        $powers[201]['maxCost'] = 0;
        $powers[201]['smilies'] = [
            'speech', 'spd', 'spfrus', 'spyay', 'spkiss', 'splap', 'splove',
            'spvamp', 'spwrite', 'spxmas', 'sppup', 'spback'];

        $powers[202]['name']    = 'vampyre';
        $powers[202]['minCost'] = 0;
        $powers[202]['maxCost'] = 0;
        $powers[202]['smilies'] = [
            'vampyre', 'vbat', 'vbheart', 'vblood', 'vcoffin',
            'vcross', 'vfangs', 'vglamour', 'vrip', 'vstake', 'vtongue'
        ];

        $powers[203]['name']    = 'treefx';
        $powers[203]['minCost'] = 0;
        $powers[203]['maxCost'] = 0;
        $powers[203]['smilies'] = ['treefx'];

        $powers[204]['name']    = 'claus';
        $powers[204]['minCost'] = 0;
        $powers[204]['maxCost'] = 0;
        $powers[204]['smilies'] = [
            'claus', 'clcool', 'cld', 'cleek', 'clgrin', 'clmad',
            'clsad', 'clsmile', 'clsweat', 'clwink', 'clx'
        ];

        $powers[205]['name']    = 'quest';
        $powers[205]['minCost'] = 0;
        $powers[205]['maxCost'] = 0;
        $powers[205]['smilies'] = [
            'quest', 'bearer', 'disappear', 'dwarf', 'goblin2',
            'queenelf', 'sneak', 'thering', 'warrior', 'wizzard'
        ];

        $powers[206]['name']    = 'lang';
        $powers[206]['minCost'] = 0;
        $powers[206]['maxCost'] = 0;
        $powers[206]['smilies'] = ['lang'];

        $powers[207]['name']    = 'quest2';
        $powers[207]['minCost'] = 0;
        $powers[207]['maxCost'] = 0;
        $powers[207]['smilies'] = [
            'quest2', '06', 'agreement', 'arrow', 'burnt', 'dwarf2',
            'dwarfz', 'elve2', 'goblin3', 'newton', 'pile'
        ];

        $powers[208]['name']    = 'glitterfx';
        $powers[208]['minCost'] = 0;
        $powers[208]['maxCost'] = 0;
        $powers[208]['smilies'] = ['glitterfx'];

        $powers[209]['name']    = 'xavi';
        $powers[209]['minCost'] = 0;
        $powers[209]['maxCost'] = 0;
        $powers[209]['smilies'] = ['xavi'];

        $powers[210]['name']    = 'kmouse';
        $powers[210]['minCost'] = 0;
        $powers[210]['maxCost'] = 0;
        $powers[210]['smilies'] = [
            'kmouse', 'kmcheer', 'kmcry', 'kmeyerub', 'kmfit', 'kmfrustrate',
            'kmglare', 'kmgrouch', 'kmhide', 'kmhug', 'kmlaugh', 'kmshock',
            'kmshuffle', 'kmsleepy', 'kmsmile', 'kmback'
        ];

        $powers[211]['name']    = 'eighties';
        $powers[211]['minCost'] = 0;
        $powers[211]['maxCost'] = 0;
        $powers[211]['smilies'] = [
            'eighties', 'bighair', 'bigphone', 'boombox', 'cassette', 'dance80',
            'dj80', 'hoverboard', 'joystick', 'skate', 'slacker1', 'slacker2',
            'poi', 'thermochrome', 'timemachine'
        ];

        $powers[212]['name']    = 'foe';
        $powers[212]['minCost'] = 0;
        $powers[212]['maxCost'] = 0;
        $powers[212]['smilies'] = ['foe'];

        $powers[213]['name']    = 'zombie';
        $powers[213]['minCost'] = 0;
        $powers[213]['maxCost'] = 0;
        $powers[213]['smilies'] = [
            'zombie', 'zombie1', 'zombie2', 'zombie3', 'zombie4', 'survivor1',
            'survivor2', 'survivor3', 'survivor4', 'bloodface', 'deadup', 'zombieback'
        ];

        $powers[214]['name']    = 'makeup';
        $powers[214]['minCost'] = 0;
        $powers[214]['maxCost'] = 0;
        $powers[214]['smilies'] = [
            'makeup', 'blush', 'comb', 'eyeliner', 'lipgloss', 'lipstick1',
            'lipstick2', 'makeupface', 'nailpolish', 'perfume', 'purse'
        ];

        $powers[215]['name']    = 'kheart';
        $powers[215]['minCost'] = 0;
        $powers[215]['maxCost'] = 0;
        $powers[215]['smilies'] = [
            'kheart', 'kharrow', 'khbub', 'khcupid', 'kheartb', 'kheyes',
            'khhug', 'khhurt', 'khily', 'khkiss', 'khlips', 'khmadly', 'khring', 'khroses'
        ];

        $powers[216]['name']    = 'kmonkey';
        $powers[216]['minCost'] = 0;
        $powers[216]['maxCost'] = 0;
        $powers[216]['smilies'] = [
            'kmonkey', 'kmoback', 'kmoblow', 'kmod', 'kmodance', 'kmofrus',
            'kmonehneh', 'kmorage', 'kmoredface', 'kmostare', 'kmoteeth',
            'kmoun', 'kmowhistle', 'kmowonder'
        ];

        $powers[217]['name']    = 'nuclear';
        $powers[217]['minCost'] = 0;
        $powers[217]['maxCost'] = 0;
        $powers[217]['smilies'] = ['nuclear', 'nuclearb'];

        $powers[218]['name']    = 'stylist';
        $powers[218]['minCost'] = 0;
        $powers[218]['maxCost'] = 0;
        $powers[218]['smilies'] = [
            'stylist', 'barber', 'mirror2', 'shave', 'shair1', 'shair2',
            'shair3', 'shair4', 'shair5', 'shair6', 'shair7'
        ];

        $powers[219]['name']    = 'spring';
        $powers[219]['minCost'] = 0;
        $powers[219]['maxCost'] = 0;
        $powers[219]['smilies'] = [
            'spring', 'bees', 'birdy', 'butterflys', 'flohat', 'flohide',
            'flowerbed', 'flowers', 'floshow', 'flowerbed', 'inflower',
            'rainbow2', 'springhat', 'watercan', 'watercan2'
        ];

        $powers[220]['name']    = 'vote';
        $powers[220]['minCost'] = 0;
        $powers[220]['maxCost'] = 0;
        $powers[220]['smilies'] = ['vote', 'bemused', 'cross', 'tick', 'placard', 'voting', 'voting2'];

        $powers[221]['name']    = 'hands2';
        $powers[221]['minCost'] = 0;
        $powers[221]['maxCost'] = 0;
        $powers[221]['smilies'] = [
            'hands2', 'pointing', 'prosper', 'peace2', 'notlistening',
            'heehee', 'hearno', 'daydreaming', 'cutthroat',
            'callme', 'crossed', 'highfive'];

        $powers[222]['name']    = 'eggs';
        $powers[222]['minCost'] = 0;
        $powers[222]['maxCost'] = 0;
        $powers[222]['smilies'] = [
            'eggs', 'chickwalk', 'eggnod', 'eggbroke', 'stripegg',
            'eggwink', 'eggsleep', 'bunnyears', 'eggtongue', 'basket2'
        ];

        $powers[224]['name']    = 'hearts';
        $powers[224]['minCost'] = 0;
        $powers[224]['maxCost'] = 0;
        $powers[224]['smilies'] = ['hearts'];

        $powers[225]['name']    = 'kfox';
        $powers[225]['minCost'] = 0;
        $powers[225]['maxCost'] = 0;
        $powers[225]['smilies'] = [
            'kfox', 'kfoxbino', 'kfoxcry', 'kfoxd', 'kfoxggl', 'kfoxinl',
            'kfoxpsy', 'kfoxshades', 'kfoxsleep', 'kfoxtant', 'kfoxtwag', 'kfoxwhat'
        ];

        $powers[226]['name']    = 'kcow';
        $powers[226]['minCost'] = 0;
        $powers[226]['maxCost'] = 0;
        $powers[226]['smilies'] = [
            'kcow', 'kwangry', 'kwbell', 'kwcry', 'kwd', 'kwfrus',
            'kwlaugh', 'kwlove', 'kwmad', 'kwnod', 'kwscratch',
            'kwsleepy', 'kwswt', 'kwwhat', 'kwyay'
        ];

        $powers[227]['name']    = 'sketch';
        $powers[227]['minCost'] = 0;
        $powers[227]['maxCost'] = 0;
        $powers[227]['smilies'] = [
            'sketch', 'skannoyed', 'skd', 'skdead', 'skfrus', 'skgrr',
            'skoo', 'sksad', 'sksix', 'sksmile', 'skwink', 'skback'
        ];

        $powers[228]['name']    = 'led';
        $powers[228]['minCost'] = 0;
        $powers[228]['maxCost'] = 0;
        $powers[228]['smilies'] = ['led'];

        $powers[229]['name']    = 'seaside';
        $powers[229]['minCost'] = 0;
        $powers[229]['maxCost'] = 0;
        $powers[229]['smilies'] = [
            'seaside', 'beachdrink', 'beachvolley', 'coconut', 'crab2',
            'dolphin2', 'flipflops', 'sandcastle', 'seatree', 'shell'
        ];

        $powers[230]['name']    = 'hair2f';
        $powers[230]['minCost'] = 0;
        $powers[230]['maxCost'] = 0;
        $powers[230]['smilies'] = [
            'hair2f', 'hair2f1', 'hair2f2', 'hair2f3', 'hair2f4',
            'hair2f5', 'hair2f6', 'hair2f7', 'hair2f8', 'hair2f9', 'hair2f10'
        ];

        $powers[231]['name']    = 'statusglow';
        $powers[231]['minCost'] = 0;
        $powers[231]['maxCost'] = 0;
        $powers[231]['smilies'] = ['statusglow'];

        self::$powers = $powers;
    }

    private static function initDevelopers()
    {
        $data = json_decode(file_get_contents('./config.json', true), true);

        self::$developers = $data['developers'];
    }
    
    private static function initAPIKeys()
    {
        $data = json_decode(file_get_contents('./config.json', true), true);

        self::$apikeys = $data['apikeys'];
    }

    private static function initConfig()
    {
        $data = json_decode(file_get_contents('./config.json', true), true);

        self::$config = $data['config'];
    }

    public static function update()
    {
        self::$update = time();

        self::updateIP2();
        self::updateVolunteers();
        self::updatePowers();
        self::updateAd();
        self::updateAPIKeys();
        self::updateConfig();
    }

    public static function updateAPIKeys()
    {
        $data = json_decode(file_get_contents('./config.json', true), true);

        self::$apikeys = $data['apikeys'];
    }

    public static function updateConfig()
    {
        $data = json_decode(file_get_contents('./config.json', true), true);

        self::$config = $data['config'];
    }

    private static function updateIP2()
    {
        $ctx = stream_context_create(['http' => ['timeout' => 5]]);
        $cpt = 0;

        do {
            $page = file_get_contents('https://xat.com/web_gear/chat/ip2.php?Ocean=' . time(), false, $ctx);
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (!empty($page)) {
            self::$ip2 = json_decode($page, true);
        }
    }
    
    private static function updateVolunteers()
    {
        $ctx = stream_context_create(['http' => ['timeout' => 2]]);
        $cpt = 0;

        do {
            $page = file_get_contents(
                'https://xat.wiki/Template:List_of_ticket_volunteers&mobileaction=toggle_view_mobile&t=' . time(),
                false,
                $ctx
            );
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (empty($page)) {
            return false;
        }
        
        $volunteers = [];
        
        preg_match_all('/<td.*?>(.*?)<\/td>/si', $page, $cell);
        foreach ($cell[1] as $key => $value) {
            if ($key & 1) {
                $value = explode(' ', strip_tags($value));// reg id
                $volunteers[] = ['regname' => $value[1], 'xatid' => trim(str_replace(['(', ')'], '', $value[2]))];
            }
        }
        
        self::$volunteers = $volunteers;
    }
    
    private static function updatePowers()
    {
        $ctx = stream_context_create(['http' => ['timeout' => 2]]);
        $cpt = 0;

        do {
            $page = file_get_contents('https://xat.com/web_gear/chat/pow2.php?Ocean=' . time(), false, $ctx);
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (empty($page)) {
            return false;
        } else {
            $page = json_decode($page, true);
        }

        $pssa   = null;
        $topsh  = null;

        for ($i = 0; $i < sizeof($page); $i++) {
            if ($page[$i][0] == 'pssa') {
                $pssa = $i;
            } elseif ($page[$i][0] == 'topsh') {
                $topsh = $i;
            }
        }

        if (empty($pssa) || empty($topsh)) {
            return false;
        }

        foreach ($page[$pssa][1] as $power => $id) {
            self::$powers[$id]['name']       = $power;
            self::$powers[$id]['minCost']    = 0;
            self::$powers[$id]['maxCost']    = 0;
            self::$powers[$id]['isLimited']  = false;
            self::$powers[$id]['isAllPower'] = false;
            self::$powers[$id]['isEpic']     = false;
            self::$powers[$id]['isGroup']    = false;
            self::$powers[$id]['isGame']     = false;
            self::$powers[$id]['isNew']      = false;
            self::$powers[$id]['smilies']    = [$power];
        }

        foreach ($page[$topsh][1] as $smiley => $id) {
            self::$powers[$id]['smilies'][] = $smiley;
        }
        
        foreach ($page[7][1] as $name => $value) {
            if ($name != 'time' && $name != "!") {
                self::$powers[$value[0]]['pawns'][] = 'h' . $name;
            }
        }
        
        $id = end($page[6][1]) >= $page[0][1]['id'] ? end($page[6][1]) : $page[0][1]['id'];
        $id = count(array_keys($page[4][1], $id + 1)) > 0 ? ($id + 1) : $id;
        $id = count(array_keys($page[4][1], $id + 2)) > 0 ? ($id + 2) : $id;
        $keys = array_keys(self::$powers);
        $last = end($keys);
        
        if ($id != $last) {
            $lastName = array_search($id, $page[6][1]) == false ? $id : array_search($id, $page[6][1]);
            self::$powers[$id]['name']       = $lastName;
            self::$powers[$id]['minCost']    = 0;
            self::$powers[$id]['maxCost']    = 0;
            self::$powers[$id]['storeCost']  = "unknown";
            self::$powers[$id]['isLimited']  = false;
            self::$powers[$id]['isAllPower'] = false;
            self::$powers[$id]['isEpic']     = false;
            self::$powers[$id]['isGroup']    = false;
            self::$powers[$id]['isGame']     = false;
            self::$powers[$id]['isNew']      = false;
            self::$powers[$id]['smilies']    = array_merge([$lastName], array_keys($page[4][1], $id));
        }

        $cpt = 0;

        do {
            $page = file_get_contents('https://xat.com/json/powers.php?Ocean=' . time(), false, $ctx);
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (empty($page)) {
            return false;
        } else {
            $page = json_decode($page, true);
        }

        foreach ($page as $id => $power) {
            if ($id === 0) {
                continue;
            }

            if (!in_array($power['s'], self::$powers[$id]['smilies'])) {
                array_unshift(self::$powers[$id]['smilies'], $power['s']);
            }

            self::$powers[$id]['name']       = $power['s'];
            self::$powers[$id]['isNew']      = isset($power['f']) && $power['f'] & 0x1000 ? true : false;
            self::$powers[$id]['isLimited']  = isset($power['f']) && $power['f'] & 0x2000 ? true : false;
            self::$powers[$id]['isEpic']     = isset($power['f']) && $power['f'] & 8 ? true : false;
            self::$powers[$id]['isGame']     = isset($power['f']) && $power['f'] & 0x80 ? true : false;
            self::$powers[$id]['isGroup']    = isset($power['f']) && $power['f'] & 0x800 ? true : false;
            self::$powers[$id]['isAllPower'] = isset($power['f']) && $power['f'] & 0x401 ? true : false;
            self::$powers[$id]['storeCost']  = $power['x'] ?? $power['d'] * 13.5;
        }

        $url = 'https://xatproject.com/fairtrade/api.php?action=powers';
        $cpt = 0;

        do {
            $page = file_get_contents($url, false, $ctx);
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (empty($page)) {
            return false;
        } else {
            $fairtrade = json_decode($page, true);
            if (isset($fairtrade['powers'])) {
                foreach ($fairtrade['powers'] as $id => $power) {
                    if (!empty($power['min_xats']) && !empty($power['max_xats'])) {
                        self::$powers[$id]['minCost'] = $power['min_xats'];
                        self::$powers[$id]['maxCost'] = $power['max_xats'];
                    } else {
                        self::$powers[$id]['minCost'] = 0;
                        self::$powers[$id]['maxCost'] = 0;
                    }
                }
            }

            ksort(self::$powers);
        }
    }

    public static function updateAd()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_URL, 'https://xat.com/json/ad.php?Ocean=' . time());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $page = curl_exec($curl);
        curl_close($curl);

        if (!$page) {
            return false;
        }

        $json = json_decode($page, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        self::$adMessage1  = $json['m1'];
        self::$adMessage2  = $json['m2'];
        self::$releaseTime = $json['t'];
    }

    public static function setLoginPacket($info)
    {
        self::$loginPacket = $info;
    }

    public static function setLoginTime($info)
    {
        self::$loginTime = $info;
    }

    public static function getLoginPacket()
    {
        if (empty(self::$loginPacket)) {
            return '';
        }

        return self::$loginPacket;
    }
    
    public static function getLoginTime()
    {
        if (empty(self::$loginTime)) {
            return 0;
        }

        return self::$loginTime;
    }

    public static function getIP2()
    {
        return self::$ip2;
    }

    public static function getVolunteers()
    {
        return self::$volunteers;
    }

    public static function getPowers()
    {
        return self::$powers;
    }

    public static function getMaxPowerIndex()
    {
        $max = max(array_keys(self::$powers)) / 32;
        if (!is_float($max)) {
            $max += 1;
        }

        return ceil($max);
    }

    public static function getRegname()
    {
        return self::$regname;
    }

    public static function getXatid()
    {
        return self::$xatid;
    }

    public static function getPassword()
    {
        return self::$password;
    }

    public static function getPin()
    {
        return self::$pin;
    }

    public static function getPw()
    {
        return self::$pw;
    }

    public static function getForceLogin()
    {
        return self::$forcelogin;
    }

    public static function getDevelopers()
    {
        return self::$developers;
    }

    public static function getReleaseTime()
    {
        return self::$releaseTime;
    }
    
    public static function getAdMessage1()
    {
        return self::$adMessage1;
    }
    
    public static function getAdMessage2()
    {
        return self::$adMessage2;
    }
    
    public static function getAPIKeys()
    {
        return self::$apikeys;
    }

    public static function getConfig()
    {
        return self::$config;
    }

    public static function getFreeSmilies()
    {
        return self::$freeSmilies;
    }
    
    public static function getDefaultName()
    {
          return self::$defaultName;
    }
}
