<?php

function getMatches($text) {

  if (preg_match_all('|(mul)\([0-9]*[1-9][0-9]*,[0-9]*[1-9][0-9]*\)|', $text, $matches)) {
    return $matches[0];
  } else {
    return false;
  }
}

function multiplyMatches($matches) {

  $subs = [];

  foreach ($matches as $match) {

    $trimStart = substr($match, 4);
    $trimEnd = rtrim($trimStart, ')');
    $numbers = explode(',', $trimEnd);
    $subs[] = $numbers[0] * $numbers[1];
  }

  return $subs;
}

function sumNumbers($numbers) {

  $total = 0;

  foreach ($numbers as $numb) {
    $total += $numb;
  }

  return $total;
}

function splitByDont($text) {

  $splits = explode("don't()", $text);

  return $splits;
}

function main($data) {

  $puzzleMainFile = fopen('data/dayThree' . ".txt", "r") or die("Unable to open file!");
  $puzzleMainData = fread($puzzleMainFile, filesize('data/dayThree' . ".txt"));

  $splitsByDont = splitByDont($puzzleMainData);

  $newString = $splitsByDont[0];

  foreach ($splitsByDont as $section) {

    $sub = explode("do()", $section);
    unset($sub[0]);

    foreach ($sub as $line) {
      $newString .= $line;
    }
  }

  //echo $newString;

  $matches = getMatches(
    $newString
  );
  print_r($matches);
  $multiplied = multiplyMatches($matches);
  $sum = sumNumbers($multiplied);
  echo $sum;
}
