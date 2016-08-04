<?php

$jinx = function ($who, $message, $type) {
    /*
        Work In Progress
        Contains a few errors
    */
    $bot = actionAPI::getBot();
    
    $jinxType = $message[1] ?? "mix";
    unset($message[0], $message[1]);
    
    $message = implode(' ', $message);
    
    $ChkSum = function($string) {
        $res = 0;
        $i = 0;
        while ($i < strlen($string)) {
            $charcode = ord($string[$i]);
            if ($charcode != 32){
                $res += $charcode;
            }
            $i++;
        }
        return $res;  
    };
    
    $randomSort = function($s) {
        if (($s & 1)){
            return (-1);
        };
        return (1);
    };
    
    $uRShift = function($a, $b) { 
        $z = hexdec(80000000); 
        if ($z & $a) 
        { 
            $a = ($a >> 1); 
            $a &= (~$z); 
            $a |= 0x40000000; 
            $a = ($a >> ($b - 1)); 
        } else { 
            $a = ($a >> $b); 
        } 
        return $a; 
    };
    
    $seed = $Rand = $ChkSum($message . $who);
    $seed = ($seed ^ ($seed << 21));
    $seed = ($seed ^ ($uRShift($seed, 35)));
    $seed = ($seed ^ ($seed << 4));
    $Arg = 100 * (4 / 50);
    
    $message = explode(' ', $message);
    $_local_9 = [];
    $i = 0;
    while ($i < count($message)) {
        if ($message[$i][0] == "(" && $message[$i][count($message[$i]) - 1] == ")"){
            $_local_9[] = $message[$i];
            $message[$i] = ">";
        };
        $i++;
    };
    
    switch (strtolower($jinxType)) {//JinxIt
        case "reverse":
            $i = 0;
            while ($i < count($message)) {
                $message[$i] = implode("", array_reverse(explode("", $message[$i])));
                $i++;
            }
            break;
        case "mix":
        default:
            $i = 0;
            while ($i < count($message)) {
                $message[$i] = implode("", sort(str_split($message[$i]), $randomSort($seed)));
                $i++;
            }
            break;
        case "ends":
            $i = 0;
            $message2 = [];
            $messageTmp = "";
            while ($i < count($message)) {
                $message2 = str_split($message[$i]);
                $messageTmp = $message2[0];
                $message2[0] = $message2[count($message2) - 1];
                $message2[count($_local_4) - 1] = $messageTmp;
                $message[$i] = implode("", $message2);
                $i++;
            }
            break;
        case "middle":
            $i = 0;
            $message2 = $message3 = [];
            $messageTmp = $messageTmp2 = "";
            while ($i < count($message)) {
                $message2 = str_split($message[$i]);
                if (count($message2) > 3) {
                    $messageTmp = $message2[0];
                    $messageTmp2 = $message2[count($message2) - 1];
                    $message3 = array_slice($message2, 1, count($message2) - 1);
                    $message3 = sort($message3, $randomSort($seed));
                    $message3 = array_unshift($message3, $messageTmp);
                    array_push($message3, $messageTmp2);
                    $message[$i] = implode("", $message3);
                }
                $i++;
            }
            break;
        case "hang":
            $messageTmp = implode(" ", $message);
            $message2 = preg_replace("/[ >]/", "", $messageTmp);
            $message2 = str_split($message2);
            if ($Arg > count($message2)){
                $Arg = count($message2);
            }
            $i = 0;
            while ($i < $Arg) {
                $messageTmp = implode("_", explode($message2[$seed % count($message2)], $messageTmp));
                $i++;
            }
            $message = implode(" ", $messageTmp);
            break;
        case "egg":
            $message = implode(' ', $message);
            $message = preg_replace( [
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
    }
    
    $i = 0;
    while ($i < count($message)) {
        if ($message[$i] == ">"){
            $message[$i] = array_pop($_local_9);
        }
        $i++;
    }
    $message = implode(" ", $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type);
    } else {
        return $bot->network->sendMessageAutoDetection($who, in_array($message[0], ['/', '#']) ? '_' . $message : $message, $type);
    }
};
