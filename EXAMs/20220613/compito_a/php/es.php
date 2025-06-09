// File: index.php

<?php
header('Content-Type: application/json');

$db = new mysqli("localhost","root","","sudoku");
// Helper: check if board string is a valid full solution (1-9 in each row, col, block)
function is_full_valid($board) {
    $grid = str_split($board);
    // rows
    for ($r = 0; $r < 9; $r++) {
        $row = array_slice($grid, $r*9, 9);
        if (count(array_unique($row)) !== 9 || in_array('0', $row, true)) return false;
    }
    // cols
    for ($c = 0; $c < 9; $c++) {
        $col = [];
        for ($r = 0; $r < 9; $r++) $col[] = $grid[$r*9 + $c];
        if (count(array_unique($col)) !== 9 || in_array('0', $col, true)) return false;
    }
    // blocks
    for ($br = 0; $br < 3; $br++) {
        for ($bc = 0; $bc < 3; $bc++) {
            $blk = [];
            for ($r = 0; $r < 3; $r++) {
                for ($c = 0; $c < 3; $c++) {
                    $idx = ($br*3 + $r)*9 + ($bc*3 + $c);
                    $blk[] = $grid[$idx];
                }
            }
            if (count(array_unique($blk)) !== 9 || in_array('0', $blk, true)) return false;
        }
    }
    return true;
}

// ACTION: New Game
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // generate initial state: 8 digits placed non-conflicting
    $initial = array_fill(0, 81, '0');
    $placed = 0;
    while ($placed < 8) {
        $pos = rand(0, 80);
        if ($initial[$pos] !== '0') continue;
        $val = strval(rand(1,9));
        $temp = $initial;
        $temp[$pos] = $val;
        if (!is_full_valid(implode('', array_map(function($c){ return $c==='0'? '' : $c; }, $temp)))) {
            // skip invalid: but full requires no zeros, so skip row/col/block conflict check manually
        }
        // Check row/col/block conflicts manually
        $grid = $initial;
        $row = intdiv($pos, 9);
        $col = $pos % 9;
        // row
        for ($c = 0; $c < 9; $c++) if ($grid[$row*9 + $c] === $val) continue 2;
        // col
        for ($r = 0; $r < 9; $r++) if ($grid[$r*9 + $col] === $val) continue 2;
        // block
        $br = intdiv($row,3)*3;
        $bc = intdiv($col,3)*3;
        for ($r = 0; $r < 3; $r++) for ($c = 0; $c < 3; $c++) if ($grid[ ($br+$r)*9 + ($bc+$c) ] === $val) continue 3;
        // place
        $initial[$pos] = $val;
        $placed++;
    }
    $state = implode('', $initial);
    // insert game
    $stmt = $db->prepare("INSERT INTO giochi (initial_state) VALUES (?)");
    $stmt->bind_param("s", $state);
    $stmt->execute();
    $game_id = $stmt->insert_id;
    setcookie('game_id', $game_id, time() + 3600, '/');
    echo json_encode(['board' => $state]);
    exit;
}

// ACTION: Validate Solution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $board = $data['board'] ?? '';
    $game_id = $_COOKIE['game_id'] ?? null;
    if (!$game_id) {
        echo json_encode(['error' => 'No game in progress']); exit;
    }
    // fetch initial
    $stmt = $db->prepare("SELECT initial_state FROM giochi WHERE id = ?");
    $stmt = $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) {
        echo json_encode(['error' => 'Invalid game']); exit;
    }
    $initial = $row['initial_state'];
    // respect initial
    for ($i = 0; $i < 81; $i++) {
        if ($initial[$i] !== '0' && $board[$i] !== $initial[$i]) {
            echo json_encode(['valid' => false]); exit;
        }
    }
    // full validity
    // reuse is_full_valid but require no zeros
    if (!is_full_valid($board)) {
        echo json_encode(['valid' => false]); exit;
    }
    echo json_encode(['valid' => true]);
    exit;
}
?>