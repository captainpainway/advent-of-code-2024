<?php

$order = [];
$pages = [];
$badpages = [];

function processInput($filename) {
    global $order, $pages;
    $file = fopen($filename, "r");
    $setorder = true;
    while ($line = fgets($file)) {
        if (empty(trim($line))) {
            // Switch from ordering to page array.
            $setorder = false;
            continue;
        }
        if ($setorder) {
            list($x, $y) = explode("|", trim($line));
            $order[$x][] = $y;
        } else {
            $pages[] = explode(",", trim($line));
        }
    }
    fclose($file);
}

function findGoodPages() {
    global $order, $pages, $badpages;
    $okpages = [];
    foreach ($pages as $pagelist) {
        foreach ($pagelist as $idx => $page) {
            if (array_key_exists($page, $order)) {
                $afterpages = $order[$page];
                $prev = array_slice($pagelist, 0, $idx);
                $breaks = array_intersect($prev, $afterpages);
                if (!empty($breaks)) {
                    $badpages[] = $pagelist; // Add to bad pages array for part 2.
                    continue 2; // Break if any pages appear in the previous pages array.
                }
            }
        }
        $okpages[] = $pagelist; // If the array is ok, add to array;
    }
    return $okpages;
}

function orderBadPages() {
    global $badpages, $order;
    $okpages = [];
    foreach ($badpages as $pagelist) {
        foreach ($pagelist as $idx => $page) {
            if (array_key_exists($page, $order)) {
                $afterpages = $order[$page];
                $prev = array_slice($pagelist, 0, $idx);
                $breaks = array_intersect($prev, $afterpages);
                if (!empty($breaks)) {
                    $pageidx = array_search($page, $pagelist);
                    $minbreak = count($pagelist);
                    foreach ($breaks as $break) {
                        // Find the earliest break in the array.
                        $minbreak = min($minbreak, array_search($break, $pagelist));
                    }
                    array_splice($pagelist, $minbreak, 0, $page);
                    array_splice($pagelist, $pageidx + 1, 1);
                }
            }
        }
        $okpages[] = $pagelist; // If the array is ok, add to array;
    }
    return $okpages;
}

function total($sortedpages) {
    $total = 0;
    foreach ($sortedpages as $pages) {
        $middle = $pages[floor(count($pages) / 2)];
        $total += (int)$middle;
    }
    return $total;
}

function part1() {
    $okpages = findGoodPages();
    echo total($okpages)."\n";
}

function part2() {
    $okpages = orderBadPages();
    echo total($okpages)."\n";
}

processInput($argv[1]);
part1();
part2();
