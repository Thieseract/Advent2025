<?php

function getSteps($line) {

  $steps = [];
  $size = sizeof($line);

  for ($i = 0; $i < ($size - 1); $i++) {

    $steps[] = $line[$i] - $line[$i + 1];
  }

  return $steps;
}

function checkStepLength($steps) {

  $allowedSteps = [1, 2, 3];
  $allowed = true;

  foreach ($steps as $step) {

    $absStep = abs($step);
    if (!in_array($absStep, $allowedSteps)) {
      $allowed = false;
    }
  }

  return $allowed;
}

function checkStepDirection($steps) {

  $ups = 0;
  $downs = 0;
  $directionGood = true;

  foreach ($steps as $step) {

    if ($step > 0) {
      $ups += 1;
    } else if ($step < 0) {
      $downs += 1;
    }
  }

  if ($ups != 0 and $downs != 0) {
    $directionGood = false;
  }

  return $directionGood;
}

function evaluateLine($line) {

  $steps = getSteps(explode(' ', $line));
  $lineIsGood = false;
  $goodLength = checkStepLength($steps);

  if ($goodLength === true) {

    $goodDirection = checkStepDirection($steps);
    if ($goodDirection) {
      $lineIsGood = true;
    }
  }

  return $lineIsGood;
}

function main($data) {

  $safeLines = 0;

  foreach ($data as $line) {

    if (evaluateLine($line)) {
      $safeLines += 1;
    }
  }

  echo $safeLines;
}
