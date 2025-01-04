<?php

class Tile {
    function __construct($i, $j, $value) {
        $this->i = $i;
        $this->j = $j;
        $this->value = (int)$value;
    }

    /**
    * Finds the number of value 9 tiles that can be found from this point 
    */
    public function findTrailheadScore($map) {
        $tiles = $this->findSurroundingTiles($this, $map);
        $count = 50;
        $scores = [];
        while(count($tiles) > 0) {
            $tile = array_pop($tiles);
            $surrounding = $this->findSurroundingTiles($tile, $map);
            foreach ($surrounding as $s) {
                if ($s->value === 9) {
                    $name = sprintf("%s, %s", $s->i, $s->j); // Only count uniques
                    $scores[$name] = $s;
                } else {
                    $tiles[] = $s;
                }
            }
            $count--;
        }
        return count($scores);
    }

    /**
    * Finds all valid hiking trails that lead to a value of 9
    */
    public function findDistinctTrails($map) {
        $tiles = $this->findSurroundingTiles($this, $map);
        $count = 50;
        $trails = [];
        while(count($tiles) > 0) {
            $tile = array_pop($tiles);
            $surrounding = $this->findSurroundingTiles($tile, $map);
            foreach ($surrounding as $s) {
                if ($s->value === 9) {
                    $trails[] = $s; // Count any valid way of getting to the end
                } else {
                    $tiles[] = $s;
                }
            }
            $count--;
        }
        return count($trails);
    }

    /**
    * Finds the adjacent tiles that are the next highest value
    */
    private function findSurroundingTiles($tile, $map) {
        $tiles = [];
        // North
        if ($tile->i > 0) {
            $test = $map[$tile->i - 1][$tile->j];
            if ($test->value === $tile->value + 1) {
                $tiles[] = $test;
            }
        }
        // South
        if ($tile->i < count($map) - 1) {
            $test = $map[$tile->i + 1][$tile->j];
            if ($test->value === $tile->value + 1) {
                $tiles[] = $test;
            }
        }
        // East
        if ($tile->j < count($map[0]) - 1) {
            $test = $map[$tile->i][$tile->j + 1];
            if ($test->value === $tile->value + 1) {
                $tiles[] = $test;
            }
        }
        // West 
        if ($tile->j > 0) {
            $test = $map[$tile->i][$tile->j - 1];
            if ($test->value === $tile->value + 1) {
                $tiles[] = $test;
            }
        }
        return $tiles;
    }
}

function processInput($filename) {
    $data = file($filename);
    $map = array_map(fn($line) => str_split(trim($line)), $data);
    foreach ($map as $i => $row) {
        foreach ($row as $j => $tile) {
            $map[$i][$j] = new Tile($i, $j, $tile);
        }
    }
    return $map;
}

function part1($map) {
    $sum = 0;
    foreach ($map as $i => $row) {
        foreach ($row as $j => $tile) {
            if ($tile->value === 0) {
                $sum += $tile->findTrailheadScore($map);
            }
        }
    }
    var_dump($sum);
}

function part2($map) {
    $sum = 0;
    foreach ($map as $i => $row) {
        foreach ($row as $j => $tile) {
            if ($tile->value === 0) {
                $sum += $tile->findDistinctTrails($map);
            }
        }
    }
    var_dump($sum);
}

$map = processInput($argv[1]);
part1($map);
part2($map);
