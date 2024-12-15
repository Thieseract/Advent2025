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
