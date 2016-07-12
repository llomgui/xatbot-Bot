<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;
use Ocean\Xat\Variables;

class Value
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!isset($message[1]) || empty($message[1])) {
            $xatusers[] = $who;
        } else {
            unset($message[0]);
            foreach ($message as $mess) {
                if (!empty($mess)) {
                    $xatusers[] = $mess;
                }
            }
        }

        $powers = Variables::getPowers();

        if (sizeof($xatusers) > 0) {
            $regname    = '';
            $storeprice = 0;
            $minprice   = 0;
            $maxprice   = 0;
            $count      = 0;
            $cdoubles   = 0;

            foreach ($xatusers as $xatuser) {
                if (is_numeric($xatuser) && isset($bot->users[$xatuser])) {
                    $user = $bot->users[$xatuser];
                } else {
                    foreach ($bot->users as $id => $object) {
                        if (is_object($object)) {
                            if (strtolower($object->getRegname()) == strtolower($xatuser)) {
                                $user = $object;
                                break;
                            }
                        }
                    }
                }

                if (isset($user)) {
                    if (!$user->isRegistered()) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            'You cannot value an unregistered account!',
                            $type
                        );
                    }

                    if (!$user->hasDays()) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            'You cannot value an account without days!',
                            $type
                        );
                    }

                    if (!isset($users[$user->getId()])) {
                        $users[$user->getId()] = $user;
                    } else {
                        continue;
                    }

                    if (sizeof($xatusers) > 1) {
                        $regname .= $user->getRegname() . ', ';
                    } else {
                        $regname .= $user->getRegname();
                    }

                    $doubles = $user->getDoubles();
                    var_dump($doubles);

                    if (!empty($doubles)) {
                        $pO = explode('|', $doubles);

                        for ($i = 0; $i < sizeof($pO); $i++) {
                            $pos = strpos($pO[$i], '=');
                            if ($pos !== false) {
                                $id     = (int)substr($pO[$i], 0, $pos);
                                $amount = (int)substr($pO[$i], $pos + 1);
                            } else {
                                $id     = (int)$pO[$i];
                                $amount = 1;
                            }

                            if ($id == 0) {
                                continue;
                            }

                            if (isset($powers[$id]['storeCost'])) {
                                if (!$powers[$id]['isLimited'] || $id == 260 || $id == 153 || $id == 248) {
                                    $storeprice += $powers[$id]['storeCost'] * $amount;
                                }
                            }

                            $minprice += $powers[$id]['minCost'] * $amount;
                            $maxprice += $powers[$id]['maxCost'] * $amount;
                            $count    += $amount;
                            $cdoubles += $amount;
                        }
                    }

                    foreach ($powers as $id => $array) {
                        if ($id == 95) {
                            continue;
                        }

                        if ($user->hasPower($id)) {
                            if (isset($array['storeCost'])) {
                                if (!$array['isLimited'] || $id == 260 || $id == 153 || $id == 248) {
                                    $storeprice += $array['storeCost'];
                                }
                            }

                            $minprice += $array['minCost'];
                            $maxprice += $array['maxCost'];
                            $count++;
                        }
                    }
                } else {
                    return $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type);
                    // TODO if user empty -> get data from userinfo
                }
            }

            if (sizeof($xatusers) > 1) {
                $regname = substr($regname, 0, strlen($regname) - 2);
            }

            $regname .= '\'s';

            $mindays  = round($minprice / 13.5);
            $maxdays  = round($maxprice / 13.5);
            $mineuros = round($minprice / 333, 2);
            $maxeuros = round($maxprice / 333, 2);
            $minUSD   = round($mineuros * 1.10, 2);
            $maxUSD   = round($maxeuros * 1.10, 2);

            $message = $regname
            . ' ['
            . $count
            . '] powers are worth '
            . number_format($minprice)
            . ' - '
            . number_format($maxprice)
            . ' xats or '
            . number_format($mindays)
            . ' - '
            . number_format($maxdays)
            . ' days or in cash worth '
            . $mineuros
            . ' - '
            . $maxeuros
            . ' euros or '
            . $minUSD
            . ' - '
            . $maxUSD
            . ' USD. Auction: '
            . number_format($storeprice)
            . ' xats.';

            $bot->network->sendMessageAutoDetection($who, $message, $type);
        }
    }
}
