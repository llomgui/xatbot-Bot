<?php

namespace OceanProject;

class Utilities
{
    public static function isValidXatID($xatid)
    {
        return ($xatid & 0xFFFFFFFF);
    }

    public static function isXatIDExist($xatid)
    {
        $fgc = file_get_contents('https://xat.me/x?id=' . $xatid);
        if (empty($fgc) || is_numeric($fgc)) {
            return false;
        } else {
            return $fgc;
        }
    }

    public static function isValidRegname($regname)
    {
        return (strlen($regname) >= 3);
    }

    public static function isRegnameExist($regname)
    {
        $fgc = file_get_contents('https://xat.me/x?name=' . $regname);
        if (empty($fgc)) {
            return false;
        } else {
            return true;
        }
    }

    public static function isChatExist($chatname)
    {
        $chatname = str_replace(' ', '_', $chatname);
        $url = 'https://xat.com/web_gear/chat/roomid.php?d=' . $chatname;
        $ctx = stream_context_create(['http' => ['timeout' => 1]]);
        $fgc = json_decode(file_get_contents($url, false, $ctx), true);

        if (!isset($fgc['id']) || !is_numeric($fgc['id'])) {
            return false;
        } else {
            return $fgc['id'];
        }
    }

    public static function getBetween($data, $start, $end)
    {
        $data = explode($start, $data);
        $data = explode($end, @$data[1]);
        return @$data[0];
    }
}
