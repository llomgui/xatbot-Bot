<?php

namespace Ocean\Xat;

class Network
{
    public $socket;
    public $logininfo;
    public $botData;
    public $xFlag     = 0;
    public $attempt   = 0;
    public $prevrpool = -1;

    public function __construct($botData)
    {
        $this->botData = $botData;
        $this->join();
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
        $this->xFlag = Variables::getIP2()['xFlag'];
        $ip2         = Variables::getIP2();

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
            $local10 = $this->getport($chatid);
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
        $ctimeout   = $infos[2];

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
        $this->socket = new Socket();

        if (!$this->connectToChat(8)) {
            return false;
        }

        $this->socket->write('y', ['r' => 8, 'v' => '0', 'u' => Variables::getXatid()]);

        $packetY = $this->socket->read(true);

        $this->socket->write('v', [
                'n' => Variables::getRegname(),
                'p' => (Variables::getForceLogin()) ? $this->getPw() : $this->passwordToHash()
            ]);

        $this->logininfo = $this->socket->read(true)['elements'];

        $this->socket->disconnect();

        if (!$this->connectToChat($this->botData['chatid'])) {
            return false;
        }

        $this->socket->write('y', [
                'r' => $this->botData['chatid'],
                'v' => '0',
                'u' => Variables::getXatid()
            ]);

        $packetY = $this->socket->read(true);

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
        $j2['c']  = $this->botData['chatid'];
        $j2['r']  = (!empty($this->botData['chatpass'])) ? $this->botData['chatpass'] : '';
        $j2['f']  = (!empty($this->botData['chatpass'])) ? '6' : '0';
        $j2['e']  = (!empty($this->botData['chatpass'])) ? '1' : '';
        $j2['u']  = $this->logininfo['i'];
        $j2['d0'] = $this->logininfo['d0'] ?? $this->logininfo['d0'];

        $maxPowerIndex = Variables::getMaxPowerIndex() + 3;
        for ($i = 2; $i <= $maxPowerIndex; $i++) {
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

        $j2['N'] = Variables::getRegname();
        $j2['n'] = $this->botData['name'];
        $j2['a'] = $this->botData['avatar'];
        $j2['h'] = $this->botData['homepage'];
        $j2['v'] = 'xat Community Project';

        $this->socket->write('j2', $j2);
    }

    private function passwordToHash()
    {
        $crc = crc32(Variables::getPassword());
        if ($crc & 0x80000000) {
            $crc ^= 0xffffffff;
            $crc += 1;
            $crc = -$crc;
        }

        return '$' . $crc;
    }

    private function getPw()
    {
        $POST['k2']          = '0';
        $POST['UserId']      = '0';
        $POST['mode']        = '0';
        $POST['Pin']         = (!empty(Variables::getPin()) ? Variables::getPin() : '0');
        $POST['ChangeEmail'] = '0';
        $POST['cp']          = '';
        $POST['NameEmail']   = Variables::getRegname();
        $POST['password']    = Variables::getPassword();
        $POST['Login']       = '';
        $stream = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($POST)
            ]
        ];
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
        $this->socket->write('m', [
            't' => $message,
            'u' => $this->logininfo['i']
        ]);
    }

    public function sendPrivateMessage($uid, $message)
    {
        $this->socket->write('p', [
            'u' => $uid,
            't' => $message
        ]);
    }

    public function sendPrivateConversation($uid, $message)
    {
        $this->socket->write('p', [
            'u' => $uid,
            't' => $message,
            's' => 2,
            'd' => $this->logininfo['i']
        ]);
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
        $this->socket->write('z', [
            'd' => $uid,
            'u' => $this->logininfo['i'] . '_0',
            't' => '/a_NF'
        ]);
    }

    public function sendTickle($uid)
    {
        $this->socket->write('z', [
            'd' => $uid,
            'u' => $this->logininfo['i'] . '_0',
            't' => '/l'
        ]);
    }

    public function sendFriendList($ids)
    {
        $node = 'f ' . $ids;
        $this->socket->write($node);
    }

    public function kick($uid, $reason, $sound = '')
    {
        $this->socket->write('c', [
            'p' => $reason.$sound,
            'u' => $uid,
            't' => '/k'
        ]);
    }

    public function ban($uid, $time, $reason, $tArgument = 'g', $gamebanid = '')
    {
        if ($time < 0) {
            $time = 1;
        }

        $time *= 3600;

        $this->socket->write('c', array_merge([
                'p' => $reason,
                'u' => $uid,
                't' => '/'. $tArgument . $time,
            ], (empty($gamebanid) ? [] : ['w' => $gamebanid])));
    }

    public function unban($uid)
    {
        $this->socket->write('c', [
            'u' => $uid,
            't' => '/u'
        ]);
    }
}
