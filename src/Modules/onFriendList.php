<?php

use xatbot\API\DataAPI;
use xatbot\Bot\XatVariables;

$onFriendList = function (array $array) {

    if (!isset($array['v'])) {
        var_dump($array);
        return;
    }

    $foo = ['B', 'M'];
    $bar = ['000000000', '000000'];

    $bot  = xatbot\API\ActionAPI::getBot();
    $list = explode(',', $array['v']);
    $list = array_diff($list, ['10101']);

    if (sizeof($list) == 0) {
        return $bot->network->sendMessageAutoDetection(
            DataAPI::get('online_command')['who'],
            'Offline',
            DataAPI::get('online_command')['type']
        );
    }

    $ctx  = stream_context_create(['http' => ['timeout' => 1]]);

    $volunteers = XatVariables::getVolunteers();
    $staffList = $bot->stafflist;
    $onlines = [];
    $availables = [];

    $volids = [];
    for ($i = 0; $i < sizeof($volunteers); $i++) {
        $volids[] = str_replace($foo, $bar, $volunteers[$i]['xatid']);
    }

    $staffids = [];
    foreach ($staffList as $id => $value) {
        $staffids[] = $id;
    }

    foreach ($list as $user) {
        if (substr($user, 0, 1) == '0') {
            $availables[] = $user;
        } else {
            $onlines[] = $user;
        }
    }

    $list = [];
    $list['online'] = [];
    $list['available'] = [];

    if (sizeof($availables) > 0) {
        foreach ($availables as $available) {
            $available = substr($available, 1);
            if (in_array($available, $volids)) {
                foreach ($volunteers as $volunteer) {
                    if ($available == str_replace($foo, $bar, $volunteer['xatid'])) {
                        $regname = $volunteer['regname'];
                    }
                }
            }
            if (in_array($available, $staffids)) {
                foreach ($staffList as $id => $key) {
                    if ($available == $id) {
                        $regname = $key['regname'];
                    }
                }
            }

            $regname = (!empty($regname) ? $regname : file_get_contents(
                'http://xat.me/x?id=' . $available,
                false,
                $ctx
            )
            );

            $list['available'][$regname] = $available;
        }
    }

    if (sizeof($onlines) > 0) {
        foreach ($onlines as $online) {
            if (in_array($online, $volids)) {
                foreach ($volunteers as $volunteer) {
                    if ($online == str_replace($foo, $bar, $volunteer['xatid'])) {
                        $regname = $volunteer['regname'];
                    }
                }
            }
            if (in_array($online, $staffids)) {
                foreach ($staffList as $id => $key) {
                    if ($online == $id) {
                        $regname = $key['regname'];
                    }
                }
            }

            $regname = (!empty($regname) ? $regname : file_get_contents('http://xat.me/x?id=' . $online, false, $ctx));

            $list['online'][$regname] = $online;
        }
    }

    $string = '';

    if (sizeof($list['available']) > 0) {
        foreach ($list['available'] as $reg => $id) {
            $string .= $reg . ' [' . str_replace($bar, $foo, $id) . '] ';
        }

        if (sizeof($list['available']) > 1) {
            $string .= 'are available! ';
        } else {
            $string .= 'is available! ';
        }
    }

    if (sizeof($list['online']) > 0) {
        foreach ($list['online'] as $reg => $id) {
            $string .= $reg . ' [' . str_replace($bar, $foo, $id) . '] ';
        }

        if (sizeof($list['online']) > 1) {
            $string .= 'are online! ';
        } else {
            $string .= 'is online! ';
        }
    }

    $bot->network->sendMessageAutoDetection(
        DataAPI::get('online_command')['who'],
        $string,
        DataAPI::get('online_command')['type']
    );

    DataAPI::unSetVariable('online_command');
};
