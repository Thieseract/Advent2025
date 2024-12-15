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
    $difference = abs($listOne[$i] - $listTwo[$i]);
    $total += $difference;
  }
  echo $total;
}
