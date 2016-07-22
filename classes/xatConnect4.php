<?php
class Connect4 
{
    public $red = 1;
    public $yellow = 2;
    public $outside = 3;
    public $empty = 0;
    public $field = [];
    public $height = [];
    public $won = false;

    public function __construct() 
    {
        $this->field  = array_fill(0, 7, [0, 0, 0, 0, 0, 0]);
        $this->height = [5, 5, 5, 5, 5, 5, 5];
    }

    public function get($column, $row) 
    {
        if (($column < 0) || ($column > 6) || ($row < 0) || ($row > 5)) {
            return $this->outside;
        } else {
            return $this->field[$column][$row];
        } 
    }

    public function set($column) 
    {
        if (($column < 0) || ($column > 6) || ($this->height[$column] < 0) || ($this->height[$column] > 5)) {
            return 666;
        } else {
            $this->field[$column][$this->height[$column]] = $this->red;
            $this->height[$column] = $this->height[$column] - 1;
            if ($this->check($column, $this->height[$column]+1, 4, $this->red, false) == true) {
                $this->won=true;
                return 1000;
            }
            if (($this->height[0] == -1) && ($this->height[1] == -1) && ($this->height[2] == -1) && ($this->height[3] == -1) && ($this->height[4] == -1)  && ($this->height[5] == -1) && ($this->height[6] == -1)) {
                return 50;
            }
            if ($this->won != true) {
                return $this->computer();       
            }
        }
    }

    public function check($x, $y, $quantity, $color, $check_bei_2) 
    {
        $yes = false;
        if ($color == $this->red) {
            $color2 = $this->yellow;
        } else {
            $color2 = $this->red;
        }
        for ($k = 0; $k <= 3; $k++) {
            $sum1 = $sum2 = $sum3 = $sum4 = $sum12 = $sum22 = $sum32 = $sum42 = 0;
            for ($j = 0; $j <= 3; $j++) {
                if ($this->get($x - $k + $j, $y) == $color) {
                    $sum1++;
                }
                if ($this->get($x, $y - $k + $j) == $color) {
                    $sum2++;
                }
                if ($this->get($x - $k + $j, $y - $k + $j) == $color) {
                    $sum3++;
                }
                if ($this->get($x + $k - $j, $y - $k + $j) == $color) {
                    $sum4++;
                }
                if ($this->get($x - $k + $j, $y) == $color2) {
                    $sum12++;
                }
                if ($this->get($x, $y - $k + $j) == $color2) {
                    $sum22++;
                }
                if ($this->get($x - $k + $j, $y - $k + $j) == $color2) {
                    $sum32++;
                }
                if ($this->get($x + $k - $j, $y - $k + $j) == $color2) {
                    $sum42++;
                }
                if ($this->get($x - $k + $j, $y) == $this->outside) {
                    $sum12++;
                }
                if ($this->get($x, $y - $k + $j) == $this->outside) {
                    $sum22++;
                }
                if ($this->get($x - $k + $j, $y - $k + $j) == $this->outside) {
                    $sum32++;
                }
                if ($this->get($x + $k - $j, $y - $k + $j) == $this->outside) {
                    $sum42++;
                }
            }
            if (($sum1 >= $quantity) && ($sum12 == 0)) {
                $yes = true;
            } elseif (($sum2 >= $quantity) && ($sum22 == 0)) {
                $yes = true;
            } elseif (($sum3 >= $quantity) && ($sum32 == 0)) {
                $yes = true;
            } elseif (($sum4 >= $quantity) && ($sum42 == 0)) {
                $yes = true;
            }
            if (($yes == true) && ($check_bei_2 == true)) {
                $sum12 = $sum22 = $sum32 = $sum42 = 0;
                $this->field[$x][$y] = $color;
                $this->height[$x]--;
                for ($j = 0; $j <= 3; $j++) {
                    if (($sum1 >= $quantity) && ($this->get($x - $k + $j, $y) == 0) && ($this->get($x - $k + $j, $this->height[$x - $k + $j] + 1) == 0)) {
                        $sum12++;
                    }
                    if (($sum2 >= $quantity) && ($this->get($x, $y - $k + $j) == 0) && ($this->get($x, $this->height[$x] + 1) == 0)) {
                        $sum22++;
                    }
                    if (($sum3 >= $quantity) && ($this->get($x - $k + $j, $y - $k + $j) == 0) && ($this->get($x - $k + $j, $this->height[$x - $k + $j] + 1) == 0)) {
                        $sum32++;
                    }
                    if (($sum4 >= $quantity) && ($this->get($x + $k - $j, $y - $k + $j) == 0) && ($this->get($x + $k - $j, $this->height[$x + $k - $j] + 1) == 0)) {
                        $sum42++;
                    }
                }
                if (($sum12 == 1) || ($sum22 == 1) || ($sum32 == 1) || ($sum42 == 1)) {
                    $yes = false;
                }
                $this->height[$x]++;
                $this->field[$x][$y] = 0;
            }
        }
        return $yes;
    }
    
    public function random() 
    {
        return (mt_rand() / mt_getrandmax());
    }

    public function computer() 
    {
        $chance = [
            13 + $this->random() * 4,
            13 + $this->random() * 4,
            16 + $this->random() * 4,
            16 + $this->random() * 4,
            16 + $this->random() * 4,
            13 + $this->random() * 4,
            13 + $this->random() * 4
        ];
        
        for ($i = 0; $i <= 6; $i++) {
            if ($this->height[$i] < 0) {
                $chance[$i] = $chance[$i] - 30000;
            }
        }
        for ($i = 0; $i <= 6; $i++) {
            if ($this->check($i, $this->height[$i], 3, $this->yellow, false) == true) {
                $chance[$i] = $chance[$i] + 20000;
            }
            if ($this->check($i, $this->height[$i], 3, $this->red, false) == true) {
                $chance[$i] = $chance[$i] + 10000;
            }
            if ($this->check($i, $this->height[$i] - 1, 3, $this->red, false) == true) {
                $chance[$i] = $chance[$i] - 4000;
            }
            if ($this->check($i, $this->height[$i] - 1, 3, $this->yellow, false) == true) {
                $chance[$i] = $chance[$i] - 200;
            }
            if ($this->check($i, $this->height[$i], 2, $this->red, false) == true) {
                $chance[$i] = $chance[$i] + 50 + $this->random() * 3;
            }
            if (($this->check($i, $this->height[$i], 2, $this->yellow, true) == true) && ($this->height[$i] > 0)) {
                $this->field[$i][$this->height[$i]] = $this->yellow;
                $this->height[$i]--;
                $count = 0;
                for ($j = 0; $j <= 6; $j++) { 
                    if($this->check($j, $this->height[$j], 3, $this->yellow, false) == true) {
                        $count++;
                    }
                }
                if ($count == 0) {
                    $chance[$i] = $chance[$i] + 60 + $this->random() * 2;
                } else {
                    $chance[$i] = $chance[$i] - 60;
                }
                $this->height[$i]++;
                $this->field[$i][$this->height[$i]] = 0;
            }
            if ($this->check($i, $this->height[$i] - 1, 2, $this->red, false) == true) {
                $chance[$i] = $chance[$i] - 10;
            }
            if ($this->check($i, $this->height[$i] - 1, 2, $this->yellow, false) == true) {
                $chance[$i] = $chance[$i] - 8;
            }
            if ($this->check($i, $this->height[$i], 1, $this->red, false) == true) {
                $chance[$i] = $chance[$i] + 5 + $this->random() * 2;
            }
            if ($this->check($i, $this->height[$i], 1, $this->yellow, false) == true) {
                $chance[$i] = $chance[$i] + 5 + $this->random() * 2;
            }
            if ($this->check($i, $this->height[$i] - 1, 1, $this->red, false) == true) {
                $chance[$i] = $chance[$i] - 2;
            }
            if ($this->check($i, $this->height[$i] - 1, 1, $this->yellow, false) == true) {
                $chance[$i] = $chance[$i] + 1;
            }
            if (($this->check($i, $this->height[$i], 2, $this->yellow, true) == true) && ($this->height[$i] > 0)) {
                $this->field[$i][$this->height[$i]] = $this->yellow;
                $this->height[$i]--;
                for ($k = 0; $k <= 6; $k++) {    
                    if (($this->check($k,$this->height[$k],3,$this->yellow,false) == true) && ($this->height[$k] > 0)) {
                        $this->field[$k][$this->height[$k]] = $this->red;
                        $this->height[$k]--;
                        for ($j = 0; $j <= 6; $j++) {
                            if ($this->check($j, $this->height[$j], 3, $this->yellow, false) == true) {
                                $chance[$i] = $chance[$i] + 2000;
                            }
                        }
                        $this->height[$k]++;
                        $this->field[$k][$this->height[$k]] = 0;
                    }
                }
                $this->height[$i]++;
                $this->field[$i][$this->height[$i]] = 0;
            }
            if (($this->check($i, $this->height[$i], 2, $this->red, true) == true) && ($this->height[$i] > 0)) {
                $this->field[$i][$this->height[$i]] = $this->red;
                $this->height[$i]--;
                for ($k = 0; $k <= 6; $k++) {
                    if (($this->check($k, $this->height[$k], 3, $this->red, false) == true) && ($this->height[$k] > 0)) {
                        $this->field[$k][$this->height[$k]] = $this->yellow;
                        $this->height[$k]--;
                        for ($j = 0; $j <= 6; $j++) {
                            if ($this->check($j, $this->height[$j], 3, $this->red, false) == true) {
                                $chance[$i] = $chance[$i] + 1000;
                            }
                        }
                        $this->height[$k]++;
                        $this->field[$k][$this->height[$k]] = 0;
                    }
                }
                $this->height[$i]++;
                $this->field[$i][$this->height[$i]] = 0;
            }
            if (($this->check($i, $this->height[$i] - 1, 2, $this->red, true) == true) && ($this->height[$i] > 1)) {
                $this->field[$i][$this->height[$i]] = $this->red;
                $this->height[$i]--;
                for ($k = 0; $k <= 6; $k++) {
                    if (($this->check($k, $this->height[$k] - 1, 3, $this->red, false) == true) && ($this->height[$k] > 0)) {
                        $this->field[$k][$this->height[$k]] = $this->yellow;
                        $this->height[$k]--;
                        for ($j = 0; $j <= 6; $j++) {
                            if ($this->check($j, $this->height[$j] - 1, 3, $this->red, false) == true) {
                                $chance[$i] = $chance[$i] - 500;
                            }
                        }
                        $this->height[$k]++;
                        $this->field[$k][$this->height[$k]] = 0;
                    }
                }
                $this->height[$i]++;
                $this->field[$i][$this->height[$i]] = 0;
            }
        }
        $column = 0;
        $x = -10000;
        for ($i = 0; $i <= 6; $i++) {
            if ($chance[$i] > $x) {
                $x = $chance[$i];
                $column = $i;
            }
        }
        if (($column < 0) || ($column > 6) || ($this->height[$column] < 0) || ($this->height[$column] > 5)) {
            echo "\nBot tried to cheat\n";
            return $this->computer();
        }
        $this->field[$column][$this->height[$column]] = $this->yellow;
        $this->height[$column] = $this->height[$column] - 1;
        if ($this->check($column, $this->height[$column] + 1, 4, $this->yellow, false) == true) {
            return [-1000, $column];
        }
        if (($this->height[0] == -1) && ($this->height[1] == -1) && ($this->height[2] == -1) && ($this->height[3] == -1) && ($this->height[4] == -1)  && ($this->height[5] == -1) && ($this->height[6] == -1)) {
            return [51, $column];
        }
        return $column;
    }
}
?>