<?php
$guard = new Guard();
$gCoords = [];
$obs = [];
$width = 0;
$height = 0;
$visitList = [];

function part1($filename) {
    global $guard, $obs, $width, $height, $visitList, $gCoords;
    $file = fopen($filename, "r");
    $i = 0;
    while ($line = fgets($file)) {
        $col = str_split($line);
        $width = count($col);
        for ($j = 0; $j < $width; $j++) {
            if ($col[$j] === "^") {
                $gCoords = [$i, $j];
                $guard->setCoords($i, $j);
            }
            if ($col[$j] === "#") {
                $obs[] = [$i, $j];
            }
        }
        $i++;
    }
    $guard->setObs($obs);
    $height = $i;
    while (!$guard->outOfBounds($width, $height)) {
        $guard->walk();
    }
    $guard->returnVisits();
}

function part2($filename) {
    global $gCoords, $obs, $width, $height, $visitList;
    $loops = 0;
    $tot = $width * $height;
    $num = 0;
    foreach ($visitList as $visits) {
        $guard = new Guard();
        $guard->setCoords($gCoords[0], $gCoords[1]);
        $newObs = $obs;
        list($x, $y) = explode(",", $visits);
        $newObs[] = [(int)$x, (int)$y];
        $guard->setObs($newObs);
        $steps = 0;
        while (!$guard->outOfBounds($width, $height)) {
            $guard->walk();
            if ($steps > $tot) {
                $loops++;
                printf("%s - %s - %s: %s, %s\n", $num, $loops, count($visitList), $x, $y);
                break;
            }
            $steps++;
        }
        $num++;
    }
    var_dump($loops);
}

class Guard {
    function __construct() {
        $this->direction = [-1, 0];
        $this->visits = [];
        $this->visitDir = [];
    }

    private $directions = [[0, 1], [1, 0], [0, -1]];

    function setCoords(int $i, int $j) {
        $this->i = $i;
        $this->j = $j;
    }

    function setObs($obs) {
        $this->obs = $obs;
    }

    function getCoords() {
        printf("%s, %s\n", $this->i, $this->j);
    }

    function outOfBounds($width, $height) {
        return $this->i < 0 || $this->j < 0 || $this->i >= $height || $this->j >= $width;
    }

    function changeDirection() {
        $this->i = $this->i - $this->direction[0];
        $this->j = $this->j - $this->direction[1];
        array_push($this->directions, $this->direction);
        $this->direction = array_shift($this->directions);
    }

    function walk() {
        if (in_array([$this->i, $this->j], $this->obs)) {
            $this->changeDirection();
        } else {
            array_push($this->visits, $this->i . "," . $this->j);
            array_push($this->visitDir, $this->i . "," . $this->j . "," . json_encode($this->direction));
            $this->i = $this->i + $this->direction[0];
            $this->j = $this->j + $this->direction[1];
        }
    }

    function returnVisits() {
        global $visitList;
        $set = array_unique($this->visits, SORT_STRING);
        $visitList = $set;
        printf("%s\n", count($set));
    }
}

part1($argv[1]);
part2($argv[1]);
