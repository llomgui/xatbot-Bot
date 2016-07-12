<?php

namespace Ocean\Xat;

abstract class Variables
{
    private static $init;
    private static $regname;
    private static $xatid;
    private static $password;
    private static $pin;
    private static $forcelogin;
    private static $ip2;
    private static $powers;
    private static $volunteers;
    private static $update;
    private static $bots;

    public static function init()
    {
        if (self::$init) {
            return false;
        }

        self::initBotAccount();
        self::initIP2();
        self::initVolunteers();
        self::initPowers();

        return self::$init = true;
    }

    private static function initBotAccount()
    {
        $data = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '../config.json', true), true);

        self::$regname    = $data['botaccount']['regname'];
        self::$xatid      = $data['botaccount']['xatid'];
        self::$password   = $data['botaccount']['password'];
        self::$pin        = $data['botaccount']['pin'];
        self::$forcelogin = $data['botaccount']['forcelogin'];
        self::$bots       = $data['bots'];
    }

    private static function initIP2()
    {
        $json      = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '../config/ip.json');
        self::$ip2 = json_decode($json, true);
    }

    private static function initVolunteers()
    {
        $json             = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '../config/volunteers.json');
        self::$volunteers = json_decode($json, true);
    }

    private static function initPowers()
    {
        $json         = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '../config/power.json');
        self::$powers = json_decode($json, true);
    }

    public static function update()
    {
        self::$update = time();

        self::updateIP2();
        self::updatePowers();
    }

    private static function updateIP2()
    {
        $ctx = stream_context_create(['http' => ['timeout' => 1]]);
        $cpt = 0;

        do {
            $page = file_get_contents('http://xat.com/web_gear/chat/ip2.php?Ocean=' . time(), false, $ctx);
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (!empty($page)) {
            self::$ip2 = json_decode($page, true);
        }
    }

    private static function updatePowers()
    {
        $ctx = stream_context_create(['http' => ['timeout' => 1]]);
        $cpt = 0;

        do {
            $page = file_get_contents('http://xat.com/web_gear/chat/pow2.php?Ocean=' . time(), false, $ctx);
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (empty($page)) {
            return false;
        } else {
            $page = json_decode($page, true);
        }

        $powers = [];
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
            $powers[$id]['name']       = $power;
            $powers[$id]['minCost']    = 0;
            $powers[$id]['maxCost']    = 0;
            $powers[$id]['isLimited']  = false;
            $powers[$id]['isAllPower'] = false;
            $powers[$id]['smilies']    = [$power];
        }

        foreach ($page[$topsh][1] as $smiley => $id) {
            $powers[$id]['smilies'][] = $smiley;
        }

        self::$powers = $powers + self::$powers;

        $cpt = 0;

        do {
            $page = file_get_contents('http://xat.com/json/powers.php?Ocean=' . time(), false, $ctx);
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

            self::$powers[$id]['isLimited']  = isset($power['r']) ? true : false;
            self::$powers[$id]['isAllPower'] = (isset($power['f']) && ($power['f'] == 1 ||
                                                                        $power['f'] == 3)) ? true : false;
            self::$powers[$id]['storeCost']  = $power['x'] ?? $power['d'] * 13.5;
        }

        $url = 'https://docs.google.com/spreadsheet/pub?key=1W0C7D4wZ_JLL8uoAUph3wTaEzFKqhTC_WTgrs37ilVI&output=csv';

        $cpt = 0;

        do {
            $page = file_get_contents($url, false, $ctx);
            $cpt++;
            usleep(300000);
        } while (empty($page) && $cpt < 5);

        if (empty($page)) {
            return false;
        } else {
            $lines  = explode(chr(0x0A), $page);
            $header = explode(',', $lines[0]);

            for ($i = 1; $i < sizeof($lines); $i++) {
                $power      = explode(',', $lines[$i]);
                $id         = $power[0];
                $isAllPower = $power[1];

                if (!isset(self::$powers[$id])) {
                    continue;
                }

                self::$powers[$id]['isAllPower'] = $isAllPower;
                self::$powers[$id]['isGroup']    = @$power[11];
                self::$powers[$id]['isEpic']     = @$power[13];

                if (empty($power[6]) || empty($power[7])) {
                    self::$powers[$id]['minCost'] = 0;
                    self::$powers[$id]['maxCost'] = 0;
                } else {
                    self::$powers[$id]['minCost'] = $power[6];
                    self::$powers[$id]['maxCost'] = $power[7];
                }
            }

            ksort(self::$powers);
        }
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
        return ceil(max(array_keys(self::$powers)) / 32);
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

    public static function getForceLogin()
    {
        return self::$forcelogin;
    }

    public static function getBots()
    {
        return self::$bots;
    }
}
