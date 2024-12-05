<?php

function createLists($filename) {
    $file = file_get_contents($filename);
    $lines = array_map(fn($line) => explode("   ", $line) ,preg_split("/\n/", trim($file)));
    foreach ($lines as $line) {
        $firstlist[] = $line[0];
        $secondlist[] = $line[1];
    }
    sort($firstlist);
    sort($secondlist);
    return [$firstlist, $secondlist];
}

function findDistances($firstlist, $secondlist) {
    $total = 0;
    foreach ($firstlist as $i => $first) {
        $second = $secondlist[$i];
        $total += abs($first - $second);
    }
    echo $total."\n";
}

function findSimilarities($firstlist, $secondlist) {
    $total = 0;
    $secondcounts = [];
    foreach ($secondlist as $item) {
        if (array_key_exists($item, $secondcounts)) {
            $secondcounts[$item] = $secondcounts[$item] + 1;
        } else {
            $secondcounts[$item] = 1;
        }
    }
    foreach ($firstlist as $item) {
        if (array_key_exists($item, $secondcounts)) {
            $total += $item * $secondcounts[$item];
        }
    }
    echo $total."\n";
}

list($firstlist, $secondlist) = createLists($argv[1]);
findDistances($firstlist, $secondlist);
findSimilarities($firstlist, $secondlist);
