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
        if ($char === '*') {
          $color = 'r';
        } else if ($char === '^' or $char === '>' or $char === '<' or $char === 'v') {
          $startingInfo = [$row, $column, $char];
          $color = 'g';
        } else {
          $color = 'w';
        }
        $GLOBALS['display'][$row][$column] = [$char, $color];
      }
      $column++;
    }
    $row++;
  }

  return $startingInfo;
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

function getNextSpot($data, $startingInfo) {

  $direction = $startingInfo[2];
  $row = $startingInfo[0];
  $column = $startingInfo[1];

  if ($direction === '^') {
    $spotToCheck = [$row - 1, $column];
  } else if ($direction === 'v') {
    $spotToCheck = [$row + 1, $column];
  } else if ($direction === '>') {
    $spotToCheck = [$row, $column + 1];
  } else if ($direction === '<') {
    $spotToCheck = [$row, $column - 1];
  }

  $spotChar = getCharFromIndex($spotToCheck);

  if ($spotChar === '.' or $spotChar === 'X') {
  } else if ($spotChar === '#') {
  } else if ($spotChar === '*') {
    return false;
  }
  changeCharColor($spotToCheck, 'r');
  writeToScreen();
}

function iterateThroughSteps($data, $startingInfo) {

  $nextSpot = getNextSpot($data, $startingInfo);
}


function main($data) {

  $startingInfo = initDisplayData($data);
  writeToScreen();
  print_r($startingInfo);

  getNextSpot($data, $startingInfo);


  // $valid = iterateThroughIndexes($data);

  // foreach ($valid as $seq) {
  //   changeSequenceColor($seq, 'g');
  // }
  //writeToScreen();

  //colorLog('Total Number of occurences : ' . sizeof($valid), 'i');
}
