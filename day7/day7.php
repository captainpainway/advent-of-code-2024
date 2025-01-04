<?php

function processFile($filename) {
    $dataArr = [];
    $file = fopen($filename, "r");
    while ($line = fgets($file)) {
        list($total, $nums) = explode(": ", trim($line));
        $numArr = array_map(fn($num) => (int)$num, explode(" ", $nums));
        $dataArr[$total] = $numArr;
    }
    return $dataArr;
}

function add($a, $b) {
    return $a + $b;
}

function mult($a, $b) {
    return $a * $b;
}

function cat($a, $b) {
    return (int)($a . $b);
}

$totalSum = [];
function part1($dataArr) {
    global $totalSum;
    foreach ($dataArr as $total => $data) {
        permutate($data, $total, 0, 'add');
    }
    var_dump(array_sum(array_unique($totalSum)));
}

function part2($dataArr) {
    global $totalSum;
    foreach ($dataArr as $total => $data) {
        permutate($data, $total, 0, 'add', true);
    }
    var_dump(array_sum(array_unique($totalSum)));
}

function permutate($data, $total, $acc = 0, $callback, $part2 = false) {
    global $totalSum;
    if (count($data) == 0) {
        if ($acc == $total) {
            $totalSum[] = $acc;
            return;
        }
        return;
    }

    $b = array_shift($data);
    $acc = $callback($acc, $b);

    permutate($data, $total, $acc, 'add', $part2);
    permutate($data, $total, $acc, 'mult', $part2);
    if ($part2) {
        permutate($data, $total, $acc, 'cat', $part2);
    }
}

$dataArr = processFile($argv[1]);
part1($dataArr);
part2($dataArr);
