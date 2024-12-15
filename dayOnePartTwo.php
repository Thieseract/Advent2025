<?php

function setupArrays($data) {

  $listOne = [];
  $listTwo = [];

  foreach ($data as $line) {

    $lineContents = explode('   ', $line);

    $listOne[] = $lineContents[0];
    $listTwo[] = $lineContents[1];
  }

  sort($listOne);
  sort($listTwo);

  return [$listOne, $listTwo];
}

function main($data) {

  $sortedData = setupArrays($data);
  $listOne = $sortedData[0];
  $listTwo = $sortedData[1];

  $size = sizeof($listOne);

  $total = 0;

  for ($i = 0; $i < $size; $i++) {

    $numberToCheck = $listOne[$i];
    $occurences = 0;
    for ($j = 0; $j < $size; $j++) {
      if ($listTwo[$j] === $numberToCheck) {
        $occurences += 1;
      }
    }
    $difference = $numberToCheck * $occurences;
    $total += $difference;
  }

  echo $total;
}
