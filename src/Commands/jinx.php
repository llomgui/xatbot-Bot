<?php

$jinx = function (int $who, array $message, int $type) {
    /*
        Work In Progress
        Contains a few errors
    */
    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'jinx')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !jinx [reverse/mix/ends/middle/hang/egg/space/rspace] [value]',
            $type
        );
    }
    
    $jinxType = strtolower($message[1]) ?? "mix";
    unset($message[0], $message[1]);
    
    $message = implode(' ', $message);
    
    $string = $message . $who;
    $Rand = 0;
    for ($i = 0; $i < strlen($string); $i++) {
        $Rand += ord($string[$i]) != 32 ? ord($string[$i]) : 0;
    }
    
    $seed = $Rand;

    $random = function () use (&$seed) {
        $seed = ($seed ^ ($seed << 21));
        
        $a = $seed;
        $b = 35;
        $z = hexdec(80000000);
        if ($z & $a) {
            $a = ($a >> 1);
            $a &= (~$z);
            $a |= 0x40000000;
            $a = ($a >> ($b - 1));
        } else {
            $a = ($a >> $b);
        }
        
        $seed = ($seed ^ ($a));
        $seed = ($seed ^ ($seed << 4));
        return ($seed);
    };
    
    $Arg = 100 * (4 / 50);
    
    $message = explode(' ', $message);
    $_local_9 = [];

    for ($i = 0; $i < count($message); $i++) {
        if ($message[$i][0] == "(" && end($message[$i]) == ")") {
            $_local_9[] = $message[$i];
            $message[$i] = ">";
        };
    };
    $jinxType2 = $jinxType;
    if ($jinxType == "jumble") {
        $jinxType2 = ($Rand % 4);
    }
    switch ($jinxType2) {//JinxIt
        case 0:
        case "reverse":
            for ($i = 0; $i < count($message); $i++) {
                $message[$i] = strrev($message[$i]);
            }
            break;
        case 1:
        case "mix":
        default:
            for ($i = 0; $i < count($message); $i++) {
                $message[$i] = str_shuffle($message[$i]);
            }
            break;
        case 2:
        case "ends":
            $message2 = [];
            $messageTmp = "";
            
            for ($i = 0; $i < count($message); $i++) {
                $message2 = str_split($message[$i]);
                $messageTmp = $message2[0];
                $message2[0] = end($message2);
                $message2[count($message2) - 1] = $messageTmp;
                $message[$i] = implode($message2);
            }
            break;
        case 3:
        case "middle":
            $message2 = $message3 = [];
            $messageTmp = $messageTmp2 = "";
            
            for ($i = 0; $i < count($message); $i++) {
                $message2 = str_split($message[$i]);
                if (count($message2) > 3) {
                    $messageTmp = $message2[0];
                    $messageTmp2 = end($message2);
                    $message3 = array_slice($message2, 1, count($message2) - 1);
                    
                    usort($message3, function ($a, $b) use (&$random) {
                        return ($random() & 1) ? -1 : 1;
                    });
                    
                    array_unshift($message3, $messageTmp);
                    $message3[] = $messageTmp2;
                    $message[$i] = implode($message3);
                }
            }
            break;
        case 14:
        case "hang":
            $messageTmp = implode(" ", $message);
            $message2 = preg_replace("/[ >]/", "", $messageTmp);
            
            if (strlen($message2) > 0) {
                $message2 = str_split($message2);
                $Arg = $Arg > count($message2) ? count($message2) : $Arg;
                
                for ($i = 0; $i < $Arg; $i++) {
                    $messageTmp = implode("_", explode($message2[$random() % count($message2)], $messageTmp));
                }
            }
            
            $message = explode(" ", $messageTmp);
            break;
        case 16:
        case "egg":
            $message = implode(' ', $message);
            $message = preg_replace(
                [
                    "/[EȄȆḔḖḘḚḜẸẺẼẾỀỂỄỆĒĔĖĘĚÈÉÊËeȅȇḕḗḙḛḝẹẻẽếềểễệēĕėęěèéêë]/",
                    "/[AÀÁÂÃÄÅĀĂĄǺȀȂẠẢẤẦẨẪẬẮẰẲẴẶḀÆǼaàáâãäåāăąǻȁȃạảấầẩẫậắằẳẵặḁæǽ]/",
                    "/[IȈȊḬḮỈỊĨĪĬĮİÌÍÎÏĲiȉȋḭḯỉịĩīĭįiìíîïĳ]/",
                    "/[OŒØǾȌȎṌṎṐṒỌỎỐỒỔỖỘỚỜỞỠỢŌÒÓŎŐÔÕÖoœøǿȍȏṍṏṑṓọỏốồổỗộớờởỡợōòóŏőôõö]/",
                    "/[UŨŪŬŮŰŲÙÚÛÜȔȖṲṴṶṸṺỤỦỨỪỬỮỰuũūŭůűųùúûüȕȗṳṵṷṹṻụủứừửữự]/",
                    "/[YẙỲỴỶỸŶŸÝyẙỳỵỷỹŷÿý]/"
                 ],
                ["egge", "egga", "eggi", "eggo", "eggu", "eggy"],
                $message
            );
            $message = explode(" ", $message);
            break;
        case 9:
        case "space":
            $message = [implode($message)];
            $message[0] = preg_replace("/[ >]/", "", $message[0]);
            break;
        case 11:
        case "rspace":
            $messageTmp = implode(" ", $message);
            $messageTmp = preg_replace("/\s+/", " ", $messageTmp);
            $messageTmp = preg_replace("/[>]/", " ", $messageTmp);
            $message = explode(" ", $messageTmp);
            $messageTmp = preg_replace("/[ ]/", "", $messageTmp);
            $message3 = [];
            if (count($message) <= 4) {
                $i = 0;
                while ($i < strlen($messageTmp)) {
                    $random2 = (($random() % 1000000) / 10000);
                    if ($random2 < 2.998) {
                        $message3[$i] = 1;
                    } elseif ($random2 < 20.649) {
                        $message3[$i] = 2;
                    } elseif ($random2 < 41.16) {
                        $message3[$i] = 3;
                    } elseif ($random2 < 55.947) {
                        $message3[$i] = 4;
                    } elseif ($random2 < 66.647) {
                        $message3[$i] = 5;
                    } elseif ($random2 < 75.035) {
                        $message3[$i] = 6;
                    } elseif ($random2 < 82.974) {
                        $message3[$i] = 7;
                    } elseif ($random2 < 88.917) {
                        $message3[$i] = 8;
                    } else {
                        $message3[$i] = 9;
                    }
                    $i = $i + $message3[$i];
                }
            } else {
                $i = 0;
                while ($i < count($message)) {
                    $message3[$i] = count($message[$i]);
                    $i++;
                }
                usort($message3, function ($a, $b) use (&$random) {
                    return ($random() & 1) ? -1 : 1;
                });
            }
            $messageTmp = preg_replace("/ /", "", $messageTmp);
            $i2 = 0;
            $i = 0;
            while ($id < count($message) - 1) {
                $i2 = ($i2 + $message2[$i]);
                $messageTmp = array_slice($messageTmp, 0, $i2) . " " . array_slice($messageTmp, $i2);
                $i2++;
                $i++;
            }
            $message = explode(" ", $messageTmp);
            break;
    }
    
    for ($i = 0; $i < count($message); $i++) {
        if ($message[$i] == ">") {
            $message[$i] = array_pop($_local_9);
        }
    }
    $message = implode(" ", $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type);
    } else {
        return $bot->network->sendMessageAutoDetection(
            $who,
            in_array($message[0], ['/', '#']) ? '_' . $message : $message,
            $type
        );
    }
};
