<?php

function initDisplayData($data) {

  $GLOBALS['display'] = [];

  $row = 0;

  $color = 'w';

  foreach ($data as $line) {

    $column = 0;
    $line = str_split($line);
    foreach ($line as $char) {
      if (!isset($GLOBALS['display'][$row][$column])) {
        $GLOBALS['display'][$row][$column] = [$char, $color];
      }
      $column++;
    }
    $row++;
  }
}

function display() {

  foreach ($GLOBALS['display'] as $row) {

    $rowText = '';

    foreach ($row as $col) {
      $rowText .= colorLog($col[0], $col[1]);
    }
    echo $rowText;
    echo PHP_EOL;
  }
}

function writeToScreen($delay = 1) {

  clearTerminal();
  display();
  usleep($delay);
}

function changeCharColor($index, $color) {

  $GLOBALS['display'][$index[0]][$index[1]][1] = $color;
}

function generateSequences($index) {

  $needle = 'XMAS';
  $sequences = [];

  $x = $index[0];
  $y = $index[1];

  //directions to check for pattern
  $down = [
    [$x, $y],
    [$x + 1, $y],
    [$x + 2, $y],
    [$x + 3, $y],
  ];

  $diagDL = [
    [$x, $y],
    [$x + 1, $y - 1],
    [$x + 2, $y - 2],
    [$x + 3, $y - 3],
  ];

  $diagDR = [
    [$x, $y],
    [$x + 1, $y + 1],
    [$x + 2, $y + 2],
    [$x + 3, $y + 3],
  ];

  $diagUL = [
    [$x, $y],
    [$x - 1, $y - 1],
    [$x - 2, $y - 2],
    [$x - 3, $y - 3],
  ];

  $diagUR = [
    [$x, $y],
    [$x - 1, $y + 1],
    [$x - 2, $y + 2],
    [$x - 3, $y + 3],
  ];

  $right = [
    [$x, $y],
    [$x, $y + 1],
    [$x, $y + 2],
    [$x, $y + 3],
  ];

  $left = [
    [$x, $y],
    [$x, $y - 1],
    [$x, $y - 2],
    [$x, $y - 3],
  ];

  $up = [
    [$x, $y],
    [$x - 1, $y],
    [$x - 2, $y],
    [$x - 3, $y],
  ];

  $sequences = [
    $down,
    $diagDL,
    $left,
    $diagUL,
    $up,
    $diagUR,
    $right,
    $diagDR,
  ];

  return $sequences;
}

function getCharFromIndex($index) {

  return $GLOBALS['display'][$index[0]][$index[1]][0];
}

function changeSequenceColor($seq, $color) {

  changeCharColor($seq[0], $color);
  changeCharColor($seq[1], $color);
  changeCharColor($seq[2], $color);
  changeCharColor($seq[3], $color);
}

function checkSequence($sequence) {

  $candidate = getCharFromIndex($sequence[0]) . getCharFromIndex($sequence[1]) . getCharFromIndex($sequence[2]) . getCharFromIndex($sequence[3]);
  changeSequenceColor($sequence, 'b');
  writeToScreen(100000);
  if ($candidate === 'XMAS') {
    changeSequenceColor($sequence, 'g');
    writeToScreen(100000);
    return true;
  }
}

function checkIndex($index) {

  $goodFinds = [];

  //valid sequence must start with X
  if (getCharFromIndex($index) !== 'X') {
    changeCharColor($index, 'r');
    writeToScreen();
  } else {
    $seqs = generateSequences($index);

    foreach ($seqs as $seq) {
      if (checkSequence($seq)) {
        $goodFinds[] = $seq;
      }
      changeSequenceColor($seq, 'r');
    }
  }

  return $goodFinds;
}

function iterateThroughIndexes($data) {

  $row = 0;
  $ultimateSeqs = [];

  foreach ($data as $line) {

    $column = 0;
    $line = str_split($line);
    foreach ($line as $char) {
      $validSeqs = checkIndex([$row, $column]);
      $ultimateSeqs = array_merge($ultimateSeqs, $validSeqs);
      writeToScreen();
      $column++;
    }
    $row++;
  }

  return $ultimateSeqs;
}

function main($data) {

  //sleep(10);
  initDisplayData($data);
  // writeToScreen();
  // exit;

  $valid = iterateThroughIndexes($data);

  foreach ($valid as $seq) {
    changeSequenceColor($seq, 'g');
  }
  writeToScreen();

  colorLog('Total Number of occurences : ' . sizeof($valid), 'i');
  // padded the outside of the array to easily see boundaries

  // foreach ($data as $line) {

  //   $line = str_split($line);
  //   foreach ($line as $char) {
  //     if ($char === '.') {
  //       colorLog($char, 'r');
  //     } else {
  //       echo $char;
  //     }
  //   }
  //   echo PHP_EOL;
  // }


  //print_r($GLOBALS['display']);
  // display();
}
