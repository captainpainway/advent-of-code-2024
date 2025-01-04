<?php

function processInput($filename) {
    $file = fopen($filename, "r");
    $line = trim(fgets($file));
    fclose($file);
    return str_split($line);
}

function createDiskMap($data) {
    $diskMap = [];
    $idNumber = 0;
    foreach ($data as $idx => $value) {
        if ($idx % 2) {
            // Odd index indicates free space.
            for ($i = 0; $i < $value; $i++) {
                $diskMap[] = '.';
            }
        } else {
            // Even index is a file block.
            for ($i = 0; $i < $value; $i++) {
                $diskMap[] = (string)$idNumber;
            }
            $idNumber++;
        }
    }
    return $diskMap;
}

function moveFileBlocks($map) {
    $ptr1 = 0;
    $ptr2 = count($map) - 1;
    while ($ptr1 < $ptr2) {
        if ($map[$ptr1] != '.') {
            $ptr1++;
        }
        if ($map[$ptr2] == '.') {
            $ptr2--;
        }
        if ($map[$ptr1] == '.' && $map[$ptr2] != '.') {
            $map[$ptr1] = $map[$ptr2];
            $map[$ptr2] = '.';
        }
    }
    return $map;
}

function moveFileChunks($map) {
    $c = 0;
    $ptr1 = count($map) - 1;
    $ptr2 = count($map) - 1;
    $freeMap = getFreeSpaceMap($map, $ptr2);
    while ($ptr1 > 0 && count($freeMap) > 0) {
        while ($map[$ptr1] == '.') {
            $ptr1--;
            $ptr2 = $ptr1;
        }
        $val = $map[$ptr1];
        while($map[$ptr2] == $map[$ptr1]) {
            $ptr2--;
            if ($ptr2 < 0) {
                break 2;
            }
        }
        $fileLen = $ptr1 - $ptr2;
        foreach ($freeMap as $idx => $freeLen) {
            if ($freeLen >= $fileLen && $idx < $ptr2) {
                for ($i = $idx; $i < $fileLen + $idx; $i++) {
                    $map[$i] = $val;
                }
                for ($i = $ptr2 + 1; $i <= $fileLen + $ptr2; $i++) {
                    $map[$i] = '.';
                }
                break;
            }
        }
        $ptr1 = $ptr2;
        $ptr2 = $ptr1;
        $freeMap = getFreeSpaceMap($map, $ptr2);
        $c++;
    }
    return $map;
}

function getFreeSpaceMap($map, $maxIdx) {
    $freeMap = [];
    $len = 0;
    foreach ($map as $idx => $value) {
        if ($idx <= $maxIdx) {
            $next = $idx < count($map) - 1 ? $map[$idx + 1] : $map[$idx];
            if ($value == '.') {
                $len++;
                if ($next != '.') {
                    $freeMap[$idx - $len + 1] = $len;
                    $len = 0;
                }
            }
        } else {
            break;
        }
    }
    return $freeMap;
}

function checksum($data) {
    $sum = 0;
    foreach ($data as $idx => $value) {
        $sum += $idx * (int)$value;
    }
    return $sum;
}

function part1($data) {
    var_dump(checksum(moveFileBlocks($data)));
}

function part2($data) {
    $chunks = moveFileChunks($data);
    var_dump(checksum($chunks));
}


$data = processInput($argv[1]);
$diskMap = createDiskMap($data);
part1($diskMap);
part2($diskMap);
