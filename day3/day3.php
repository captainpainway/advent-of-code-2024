<?php

function scanForInstructions($file) {
    preg_match_all('/mul\(\d{1,3},\d{1,3}\)/', $file, $matches);
    return $matches[0];
}

function multiplyMatches($matches) {
    $total = 0;
    foreach ($matches as $match) {
        preg_match_all('/\d{1,3}/', $match, $nums);
        list($x, $y) = $nums[0];
        $total += $x * $y;
    }
    echo $total."\n";
}

function getDoStrings($input) {
    preg_match_all("/(do\(\))|(don\'t\(\))/", $input, $matches, PREG_OFFSET_CAPTURE);
    $dostrings = "";
    $start = null;
    foreach ($matches[0] as $match) {
        if ($match[0] == "do()" && is_null($start)) {
            $start = $match[1];
        }
        if ($match[0] == "don't()" && !is_null($start)) {
            $dostrings .= substr($input, $start, $match[1] - $start);
            $start = null;
        }
    }
    return $dostrings;
}

function part1($input) {
    $file = trim(file_get_contents($input, "r"));
    multiplyMatches(scanForInstructions($file));
}

function part2($input) {
    $file = trim(file_get_contents($input, "r"));
    multiplyMatches(scanForInstructions(getDoStrings('do()'.$file."don't()")));
}

part1($argv[1]); // 170778545
part2($argv[1]);

// 1929393071 too high
// 80077193 too low
