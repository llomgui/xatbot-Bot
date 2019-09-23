<?php

namespace xatbot;

class Utilities
{
    public static function isValidXatID($xatid)
    {
        return ((int) $xatid & 0xFFFFFFFF);
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

    public static function strposRecursive($haystack, $needle, $offset = 0, &$results = [])
    {
        $offset = strpos($haystack, $needle, $offset);
        if ($offset === false) {
            return $results;
        } else {
            $results[] = $offset;
            return self::strposRecursive($haystack, $needle, ($offset + 1), $results);
        }
    }

    public static function arrayRandomAssoc($arr, $valueOnly = false, $num = 1)
    {
        $keys = array_keys($arr);
        shuffle($keys);

        $r = [];
        for ($i = 0; $i < $num; $i++) {
            if ($valueOnly !== true) {
                $r[$keys[$i]] = $arr[$keys[$i]];
            } else {
                $r[] = $arr[$keys[$i]];
            }
        }

        return $r;
    }
}
