<?php
$width = 0;
$height = 0;

function processInput($filename) {
    global $width, $height;
    $map = [];
    $file = fopen($filename, "r");
    $i = 0;
    while ($line = fgets($file)) {
        $locs = str_split(trim($line));
        $width = count($locs);
        foreach ($locs as $j => $loc) {
            if ($loc != '.') {
                $map[$loc][] = [$i, $j];
            }
        }
        $i++;
    }
    $height = $i;
    return $map;
}

function part1($map) {
    global $width, $height;
    $anodes = [];
    foreach ($map as $freq) {
        for ($i = 0; $i < count($freq); $i++) {
            for ($j = $i + 1; $j < count($freq); $j++) {
                $rise = $freq[$j][0] - $freq[$i][0];
                $run = $freq[$j][1] - $freq[$i][1];

                $anodes[] = anodesUp($freq[$j][0], $freq[$j][1], $rise, $run);
                $anodes[] = anodesDown($freq[$i][0], $freq[$i][1], $rise, $run);
            }
        }
    }
    var_dump($anodes);
    var_dump(count(array_unique($anodes)));
}

function part2($map) {
    global $width, $height;
    $anodes = [];
    foreach ($map as $freq) {
        for ($i = 0; $i < count($freq); $i++) {
            for ($j = $i + 1; $j < count($freq); $j++) {
                $rise = $freq[$j][0] - $freq[$i][0];
                $run = $freq[$j][1] - $freq[$i][1];

                /*recurse($freq, $i, $j, $rise, $run, $anodes);*/
                $anodes[] = anodesUp($freq[$j][0], $freq[$j][1], $rise, $run, 'anodesUp');
                $anodes[] = anodesDown($freq[$i][0], $freq[$i][1], $rise, $run, 'anodesDown');
            }
        }
    }
    var_dump($anodes);
    var_dump(count(array_unique($anodes)));
}

                function recurse($freq, $i, $j, $rise, $run, &$anodes) {
                    $anodes[] = anodesUp($freq[$j][0], $freq[$j][1], $rise, $run, 'anodesUp');
                    $anodes[] = anodesDown($freq[$i][0], $freq[$i][1], $rise, $run, 'anodesDown');
                }

function anodesUp($x, $y, $rise, $run, $callback = null) {
    global $width, $height;
    $x1 = $x + $rise;
    $y1 = $y + $run;
    if (($x1 >= 0 && $x1 < $height) && ($y1 >= 0 && $y1 < $width)) {
        if ($callback) {
            $callback($x1, $y1, $rise, $run, $callback);
        }
        return $x1 . ", " . $y1;
    }
    return $x . ", " . $y;
}

function anodesDown($x, $y, $rise, $run, $callback = null) {
    global $width, $height;
    $x2 = $x - $rise;
    $y2 = $y - $run;
    if (($x2 >= 0 && $x2 < $height) && ($y2 >= 0 && $y2 < $width)) {
        if ($callback) {
            $callback($x2, $y2, $rise, $run, $callback);
        }
        return $x2 . ", " . $y2;
    }
    return $x . ", " . $y;
}

$map = processInput($argv[1]);
part1($map);
part2($map);
