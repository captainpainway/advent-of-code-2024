<?php

$unsafes = [];

function findSafeReports($filename) {
    global $unsafes;
    $total = 0;
    $file = new SplFileObject($filename);
    while (!$file->eof()) {
        $line = $file->fgets();
        if (strlen($line) > 0) {
            $arr = explode(" ", trim($line));
            $isOrder = checkOrder($arr, true);
            $isSafe = false;
            if ($isOrder) {
                $isSafe = checkJumps($arr, true);
            }
            if ($isSafe && $isOrder) {
                $total += 1;
            }
        }
    }
    echo $total."\n";
    return $total;
}

function checkOrder($arr, $setUnsafes = false) {
    global $unsafes;
    $sarr = $arr;
    $rarr = $arr;
    sort($sarr);
    rsort($rarr);
    if ($arr != $sarr && $arr != $rarr) {
        // Check if all increasing or decreasing
        if ($setUnsafes) {
            $unsafes[] = $arr;
        }
        return false;
    }
    return true;
}

function checkJumps($arr, $setUnsafes = false) {
    global $unsafes;
    while (current($arr)) {
        $curr = current($arr);
        $next = next($arr);
        if ($next) {
            $diff = abs($curr - $next);
            if ($diff > 3 || $diff < 1) {
                if ($setUnsafes) {
                    $unsafes[] = $arr;
                }
                return false;
            }
        }
        $curr = $next;
    }
    return true;
}


(int) $safetotal = findSafeReports($argv[1]);

function findDampenedReports() {
    global $unsafes, $safetotal;
    $total = 0;
    foreach ($unsafes as $arr) {
        for ($i = 0; $i < count($arr); $i++) {
            $tmp = $arr;
            // Brute-force iterate through the permutations with 1 number removed
            array_splice($tmp, $i, 1);
            if (checkOrder($tmp) && checkJumps($tmp)) {
                $total += 1;
                break;
            }
        }
    }
    echo $safetotal + $total."\n";
}

findDampenedReports();
