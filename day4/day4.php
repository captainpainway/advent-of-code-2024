<?php

function loadPuzzle($filename) {
    $file = file_get_contents($filename);
    $arr = explode("\n", trim($file));
    return array_map(fn($row) => str_split($row), $arr);
}

function part1($filename) {
    $total = 0;
    $arr = loadPuzzle($filename);
    $height = count($arr);
    for ($i = 0; $i < $height; $i++) {
        $width = count($arr[$i]);
        for ($j = 0; $j < $width; $j++) {
            $letter = $arr[$i][$j];
            $directions = [
                [0, -1], // Left horizontal
                [-1, -1], // Left top diagonal
                [-1, 0], // Top
                [-1, 1], // Right top diagonal
                [0, 1], // Right horizontal
                [1, 1], // Right lower diagonal
                [1, 0], // Bottom
                [1, -1], // Left lower diagonal
            ];
            if ($letter == "X") {
                foreach ($directions as $dir) {
                    try {
                        $str = $letter . 
                        $arr[$i + $dir[0]][$j + $dir[1]] .
                        $arr[$i + $dir[0] * 2][$j + $dir[1] * 2] .
                        $arr[$i + $dir[0] * 3][$j + $dir[1] * 3];
                        if ($str == "XMAS") {
                            $total += 1;
                        }
                    } catch (Exception $e) {}
                }
            }
        }
    }
    echo $total."\n";
}

function part2($filename) {
    $total = 0;
    $arr = loadPuzzle($filename);
    $height = count($arr);
    for ($i = 0; $i < $height; $i++) {
        $width = count($arr[$i]);
        for ($j = 0; $j < $width; $j++) {
            $letter = $arr[$i][$j];
            $directions = [
                [-1, -1], // Left top diagonal
                [-1, 1], // Right top diagonal
                [1, 1], // Right lower diagonal
                [1, -1], // Left lower diagonal
            ];
            if ($letter == "A") {
                $str = $letter;
                foreach ($directions as $dir) {
                    try {
                        $str .= $arr[$i + $dir[0]][$j + $dir[1]];
                    } catch (Exception $e) {}
                }
                if ($str == "AMMSS" || $str == "ASSMM" || $str == "AMSSM" || $str == "ASMMS") {
                    $total += 1;
                }
            }
        }
    }
    echo $total."\n";
}

@part1($argv[1]);
@part2($argv[1]);
