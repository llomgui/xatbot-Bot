<?php

namespace OceanProject\Bot;

use OceanProject\Models\Bot;

class XatNetwork
{
    public $socket;
    public $logininfo;
    public $data;
    public $xFlag     = 0;
    public $attempt   = 0;
    public $prevrpool = -1;
    public $idleTime  = 0;
    public $idleLimit = (60 * 20);// 20 minutes / 1200

    public function __construct(Bot $data)
    {
        $this->data = $data;
        $this->join();
    }

    public function tick()
    {
        if ($this->socket->isConnected()) {
            if ($this->idleTime < (time() - $this->idleLimit)) {
                $this->write(
                    'c',
                    [
                    'u' => XatVariables::getXatid(),
                    't' => '/KEEPALIVE'
                    ]
                );
            }
        }
    }

    public function getDom($arg1)
    {
        if ($this->xFlag & 8) {
            return (rand(0, 3));
        }

        if (intval($arg1) == 8) {
            return 0;
        }

        return ((intval($arg1) < 8) ? 3 : (intval($arg1) & 96) >> 5);
    }

    public function getPort($arg1)
    {
        if ($this->xFlag & 8) {
            return ((10000 + 7) + rand(0, 31));
        }

        if (intval($arg1) == 8) {
            return 10000;
        }

        return ((intval($arg1) < 8) ? ((10000 - 1) + intval($arg1)) : ((10000 + 7) + intval($arg1) % 32));
    }

    public function pickIP($chatid)
    {
        $this->xFlag = XatVariables::getIP2()['xFlag'];
        $ip2         = XatVariables::getIP2();

        if ($this->attempt >= sizeof($ip2['order'])) {
            $return = [0, 0, 0];
        }

        $local2 = $ip2['order'][$this->attempt][0];
        $local3 = $ip2[$local2];
        $local4 = $ip2['order'][$this->attempt][1];

        if ($local3[0] & 1 == 1) {
            $local5 = '0';
            if ($this->prevrpool == -1) {
                $local5 = floor((mt_rand(0, 10) / 10) * (sizeof($local3) - 1)) + 1;
            } else {
                $local5 = ($local5 % (sizeof($local3) - 1)) + 1;
            }

            if (!isset($local3[$local5])) {
                $local5--;
            }

            $local6 = floor((mt_rand(0, 10) / 10) * (sizeof($local3[$local5])));
            if (!isset($local3[$local5][$local6])) {
                $local6--;
            }

            $local7 = explode(':', $local3[$local5][$local6]);

            if (@!$local7[1]) {
                $local7[1] = 10000;
            }

            if (@!$local7[2]) {
                $local7[2] = 39;
            }

            $local8 = (intval($local7[1]) + floor((mt_rand(0, 10) / 10) * intval($local7[2])));
            $this->prevrpool = $local5;

            $return = [$local7[0], $local8, $local4];
        } else {
            $local9  = $this->getDom($chatid);
            $local10 = $this->getPort($chatid);
            $local11 = $local3[1][(4 * $local9) + floor((mt_rand(0, 10) / 10) * 4)];
            $return  = [$local11, $local10, $local4];
        }

        return $return;
    }

    public function connectToChat($chatid)
    {
        $infos      = $this->pickIP($chatid);
        $sockdomain = $infos[0];
        $useport    = $infos[1];
        $ctimeout   = 1; // $infos[2];

        echo 'IP: ' . $sockdomain . ' PORT: ' . $useport . ' ROOM: ' . $chatid . ' BotID: ' . $this->data->id . PHP_EOL;

        if ($this->socket->connect($sockdomain, $useport, $ctimeout)) {
            return true;
        } else {
            usleep(30000);
            $this->attempt++;
            return $this->connectToChat($chatid);
        }

        return false;
    }

    public function join()
    {
        $this->socket = new XatSocket();

        if (empty(XatVariables::getLoginPacket()) || (time() - XatVariables::getLoginTime()) > 900) {
            XatVariables::update();
            if (!$this->connectToChat(8)) {
                return false;
            }

            $this->write(
                'y',
                [
                    'r' => 8,
                    'v' => '0',
                    'u' => XatVariables::getXatid()
                ]
            );

            $this->socket->read(true);

            $this->write(
                'v',
                [
                    'n' => XatVariables::getRegname(),
                    'p' => (XatVariables::getForceLogin()) ? $this->getPw() : $this->passwordToHash()
                ]
            );

            $this->logininfo = $this->socket->read(true)['elements'];
            $this->socket->disconnect();

            XatVariables::setLoginPacket($this->logininfo);
            XatVariables::setLoginTime(time());
        }

        $this->logininfo = XatVariables::getLoginPacket();
        if (!$this->connectToChat($this->data->chatid)) {
            return false;
        }

        $this->write(
            'y',
            [
                'r' => $this->data->chatid,
                'v' => '0',
                'u' => XatVariables::getXatid(),
                'z' => '8335799305056508195'
            ]
        );

        $packetY = $this->socket->read(true);

        if (empty($packetY)) {
            $this->socket->disconnect();
            return $this->join();
        }

        $j2['cb'] = time();
        $j2['l5'] = '65535';
        $j2['l4'] = rand(10, 500);
        $j2['l3'] = rand(10, 500);
        $j2['l2'] = '0';
        $j2['q']  = '1';
        $j2['y']  = $packetY['elements']['i'];
        $j2['k']  = $this->logininfo['k1'];
        $j2['k3'] = $this->logininfo['k3'];

        if (isset($this->logininfo['d1'])) {
            $j2['d1'] = $this->logininfo['d1'];
        }

        $j2['z']  = 12;
        $j2['p']  = '0';
        $j2['c']  = $this->data->chatid;

        if (!empty($this->data->chatpw)) {
            $j2['r']  = $this->data->chatpw;
            $j2['f']  = 6;
            $j2['e']  = 1;
        } else {
            $j2['f']  = '0';
        }

        $j2['u']  = $this->logininfo['i'];
        $maxPowerIndex = XatVariables::getMaxPowerIndex();

        if ($this->data->premium < time() || $this->data->premiumfreeze > 1) {
            $j2['m0'] = 2147483647;
            $j2['m1'] = 2147483647;
            $j2['m2'] = 4294836223;
            $j2['m3'] = 4294967295;

            for ($i = 4; $i < $maxPowerIndex; $i++) {
                $j2['m' . $i] = 2147483647;
            }
        } else {
            $powersdisabled = [];
            $powerslist = json_decode($this->data->powersdisabled, true);
            for ($i = 0; $i < sizeof($powerslist); $i++) {
                if (array_key_exists($powerslist[$i], XatVariables::getPowers())) {
                    @$powersdisabled[(int)($powerslist[$i] / 32)] += pow(2, ($powerslist[$i] % 32));
                }
            }

            for ($i = 0; $i < $maxPowerIndex; $i++) {
                if (isset($powersdisabled[$i])) {
                    $j2['m' . $i] = $powersdisabled[$i];
                }
            }
        }

        $j2['d0'] = $this->logininfo['d0'] ?? $this->logininfo['d0'];

        for ($i = 2; $i <= ($maxPowerIndex + 3); $i++) {
            if (isset($this->logininfo['d' . $i])) {
                $j2['d' . $i] = $this->logininfo['d' . $i];
            }
        }

        if (isset($this->logininfo['dO'])) {
            $j2['dO'] = $this->logininfo['dO'];
        }

        if (isset($this->logininfo['dx'])) {
            $j2['dx'] = $this->logininfo['dx'];
        }

        if (isset($this->logininfo['dt'])) {
            $j2['dt'] = $this->logininfo['dt'];
        }

        $j2['N'] = XatVariables::getRegname();
        $j2['n'] = $this->data->nickname . '##' . $this->data->status;
        $j2['a'] = $this->data->avatar . '#' . $this->data->pcback;
        $j2['h'] = $this->data->homepage;
        $j2['v'] = 'xat Community Project';

        $this->write('j2', $j2);
    }

    public function write($node = null, $elements = [])
    {
        if ($node != 'z') {
            $this->idleTime = time();
        }
        $this->socket->write($node, $elements);
    }

    private function passwordToHash()
    {
        $crc = crc32(XatVariables::getPassword());
        if ($crc & 0x80000000) {
            $crc ^= 0xffffffff;
            $crc += 1;
            $crc = -$crc;
        }

        return '$' . $crc;
    }

    private function getPw()
    {
        if (!empty(XatVariables::getPw())) {
            return XatVariables::getPw();
        }

        $POST['k2']          = '0';
        $POST['UserId']      = '0';
        $POST['mode']        = '0';
        $POST['Pin']         = (!empty(XatVariables::getPin()) ? XatVariables::getPin() : '0');
        $POST['ChangeEmail'] = '0';
        $POST['cp']          = '';
        $POST['NameEmail']   = XatVariables::getRegname();
        $POST['password']    = XatVariables::getPassword();
        $POST['Login']       = '';
        
        $stream = [];
        $stream['http']['method'] = 'POST';
        $stream['http']['header'] = 'Content-Type: application/x-www-form-urlencoded';
        $stream['http']['content'] = http_build_query($POST);
        
        $res = file_get_contents('http://xat.com/web_gear/chat/register.php', false, stream_context_create($stream));

        if (strpos($res, 'style="color:#FF0000"><strong>**')) {
            exit('Bad password or update PIN!');
            return false;
        } else {
            $r = explode('&pw=', $res);
            if (isset($r[1])) {
                $r = explode('"', $r[1]);
                return $r[0];
            }
        }
    }

    public function reconnect()
    {
        $this->socket->disconnect();
        $this->join();
    }

    public function parseID($uid)
    {
        return explode('_', $uid)[0];
    }

    public function sendMessage($message)
    {
        $this->write(
            'm',
            [
            't' => $message,
            'u' => $this->logininfo['i']
            ]
        );
    }

    public function sendPrivateMessage($uid, $message)
    {
        $this->write(
            'p',
            [
            'u' => $uid,
            't' => $message
            ]
        );
    }

    public function sendPrivateConversation($uid, $message)
    {
        $this->write(
            'p',
            [
            'u' => $uid,
            't' => $message,
            's' => 2,
            'd' => $this->logininfo['i']
            ]
        );
    }

    public function sendMessageAutoDetection($uid, $message, $type, $sensitive = false)
    {
        //$sensitive - if type is Main but you dont want to send info to Main
        if ($sensitive && $type == 1) {
            $type = 2;
        }

        if ($type == 1) {
            $this->sendMessage($message);
        } elseif ($type == 2) {
            $this->sendPrivateMessage($uid, $message);
        } elseif ($type == 3) {
            $this->sendPrivateConversation($uid, $message);
        }
    }

    public function answerTickle($uid)
    {
        $this->write(
            'z',
            [
            'd' => $uid,
            'u' => $this->logininfo['i'] . '_0',
            't' => '/a_NF'
            ]
        );
    }

    public function sendTickle($uid)
    {
        $this->write(
            'z',
            [
            'd' => $uid,
            'u' => $this->logininfo['i'] . '_0',
            't' => '/l'
            ]
        );
    }

    public function sendFriendList($ids)
    {
        $node = 'f ' . $ids;
        $this->write($node);
    }

    public function kick($uid, $reason, $sound = '')
    {
        $this->write(
            'c',
            [
            'p' => $reason.$sound,
            'u' => $uid,
            't' => '/k'
            ]
        );
    }

    public function ban($uid, $time, $reason, $tArgument = 'g', $gamebanid = '')
    {
        if ($time < 0) {
            $time = 1;
        }

        $time *= 3600;

        $this->write(
            'c',
            array_merge(
                [
                'p' => $reason,
                'u' => $uid,
                't' => '/'. $tArgument . $time,
                ],
                (empty($gamebanid) ? [] : ['w' => $gamebanid])
            )
        );
    }

    public function unban($uid)
    {
        $this->write(
            'c',
            [
            'u' => $uid,
            't' => '/u'
            ]
        );
    }

    public function changeRank($uid, $rank)
    {
        $rankCmd = ['owner' => '/M', 'moderator' => '/m', 'member' => '/e', 'guest' => '/r'];
        $this->socket->write(
            'c',
            [
            'u' => $uid,
            't' => $rankCmd[$rank]
            ]
        );
    }

    public function tempRank($uid, $rank, $hours)
    {
        if (empty($hours) || $hours < 1 || $hours > 24) {
            $hours = 1;
        }
        $rankCmd = ['owner' => '/mo', 'moderator' => '/m', 'member' => '/mb'];
        $this->sendPrivateConversation($uid, $rankCmd[$rank] . $hours);
    }

    public function findPowerMatch($string)
    {
        $powers = XatVariables::getPowers();

        if (is_numeric($string)) {
            if (isset($powers[$string])) {
                return [$string, true];
            }
            return false;
        }
        $distance = -1;
        $closest = false;

        foreach ($powers as $id => $info) {
            $lev = levenshtein($string, $info['name']);
            if ($lev == 0) {
                return [$id, true];
            }
            if ($lev <= $distance || $distance < 0) {
                $closest = $id;
                $distance = $lev;
            }
        }

        if ($closest) {
            return [$closest, false];
        }

        return false;
    }
}
