<?php

function processInput($filename) {
    $data = file($filename);
    return explode(" ", trim($data[0]));
}

function part1($stones, $blinks) {
    while ($blinks > 0) {
        $len = count($stones);
        for ($i = $len - 1; $i >= 0; $i--) {
            $stone = $stones[$i];
            if ($stone == "0") {
                $stones[$i] = "1";
            } else if (strlen($stone) % 2 == 0) {
                $split = str_split($stone, strlen($stone) / 2);
                $stones[$i] = (string)+$split[0];
                $stones[] = (string)+$split[1];
            } else {
                $stones[$i] = (string)((int)$stone * 2024);
            }
        }
        $blinks--;
    }
    var_dump(count($stones));
}

function part2($stones, $blinks) {
    $dict = [];
    foreach ($stones as $stone) {
        $dict[$stone] = 1;
    }
    while ($blinks > 0) {
        $len = count($dict);
        $newstones = [];
        foreach ($dict as $stone => $val) {
            if ($stone == "0") {
                $newstones["1"] += $val;
            } else if (strlen($stone) % 2 == 0) {
                $split = str_split($stone, strlen($stone) / 2);
                foreach ($split as $s) {
                    $s = (string)+$s;
                    $newstones[$s] += $val;
                }
            } else {
                $num = (int)$stone * 2024;
                $newstones[(string)$num] += $val;
            }
        }
        $dict = $newstones;
        $blinks--;
    }
    var_dump(array_sum(array_values($dict)));
}

$stones = processInput($argv[1]);
part1($stones, 25);
@part2($stones, 75);
