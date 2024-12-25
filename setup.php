<?php

function loadPuzzleDataAsArray($puzzle) {

  $puzzleMainFile = fopen('data/' . $puzzle . ".txt", "r") or die("Unable to open file!");
  $puzzleMainData = fread($puzzleMainFile, filesize('data/' . $puzzle . ".txt"));
  $puzzleArray = explode(PHP_EOL, $puzzleMainData);

  $puzzleTestFile = fopen('data/' . $puzzle . "Test.txt", "r") or die("Unable to open file!");
  $puzzleTestData = fread($puzzleTestFile, filesize('data/' . $puzzle . "Test.txt"));
  $puzzleTestArray = explode(PHP_EOL, $puzzleTestData);

  return [$puzzleArray, $puzzleTestArray];
}

function loadPuzzle($puzzle, $test) {

  require_once('day' . $puzzle[0] . 'Part' . $puzzle[1] . '.php');

  $data = loadPuzzleDataAsArray('day' . $puzzle[0]);

  if ($test) {
    $retData = $data[1];
  } else {
    $retData = $data[0];
  }

  return $retData;
}

function colorLog(string $str, string $type = 'i') {
  $colors = [
    'r' => 31, //error
    'b' => 34, //success
    'g' => 32, //warning
    'w' => 97, //warning
    'i' => 36  //info
  ];
  $color = $colors[$type] ?? 0;
  echo "\033[" . $color . "m" . $str . "\033[0m";
}

function colorRow(string $str, string $type = 'i') {
  $colors = [
    'r' => 31, //error
    'b' => 34, //success
    'g' => 32, //warning
    'w' => 97, //warning
    'i' => 36  //info
  ];
  $color = $colors[$type] ?? 0;
  return "\033[" . $color . "m" . $str . "\033[0m";
}

function clearTerminal() {

  //echo "\e[H\e[J";
  echo "\e[H";
}
