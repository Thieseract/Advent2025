<?php

function loadPuzzleDay5($puzzle) {

  $puzzleMainFile = fopen('data/' . $puzzle . ".txt", "r") or die("Unable to open file!");
  $puzzleMainData = fread($puzzleMainFile, filesize('data/' . $puzzle . ".txt"));
  $puzzleArray = explode(PHP_EOL . PHP_EOL, $puzzleMainData);

  $puzzleTestFile = fopen('data/' . $puzzle . "Test.txt", "r") or die("Unable to open file!");
  $puzzleTestData = fread($puzzleTestFile, filesize('data/' . $puzzle . "Test.txt"));
  $puzzleTestArray = explode(PHP_EOL . PHP_EOL, $puzzleTestData);

  return [$puzzleArray, $puzzleTestArray];
}

function isInOrder($rule, $set) {

  $firstKey = array_search($rule[0], $set);
  $secondKey = array_search($rule[1], $set);

  //echo $firstKey . '; ' . $secondKey . PHP_EOL;
  if ($firstKey < $secondKey) {
    return true;
  }
}

function checkRules($rules, $set) {

  $goodSet = true;

  foreach ($rules as $rule) {

    $ruleData = explode('|', $rule);

    if (in_array($ruleData[0], $set) and in_array($ruleData[1], $set)) {
      //   echo $rule . PHP_EOL;
      //   print_r($set);
      if (isInOrder($ruleData, $set)) {
        continue;
      } else {
        $goodSet = false;
        break;
      }
    }
  }
  if ($goodSet) {
    return $set;
  }
}

function reOrder($rule, $set) {

  $firstKey = array_search($rule[0], $set);
  $secondKey = array_search($rule[1], $set);

  //echo $firstKey . '; ' . $secondKey . PHP_EOL;
  if ($firstKey < $secondKey) {
    return true;
  } else {
    $set[$firstKey] = $rule[1];
    $set[$secondKey] = $rule[0];
    return $set;
  }
}

function getGoodSets($rules, $sets) {

  $goodSets = [];
  $badSets = [];

  foreach ($sets as $set) {

    $setData = explode(',', $set);
    $results = checkRules($rules, $setData);
    if ($results) {
      $goodSets[] = $results;
    } else {
      $badSets[] = $set;
    }
  }

  return $badSets;
}

function addGoodSets($sets) {

  $total = 0;

  //print_r($sets);

  foreach ($sets as $set) {
    $length = sizeof($set);
    $middle = floor($length / 2);
    // echo 'Set to test' . PHP_EOL;
    // print_r($set);
    // echo 'This is the middle' . $middle . PHP_EOL . PHP_EOL;
    $total += $set[$middle];
  }

  echo $total;
}

function isRuleInSet($rule, $set) {

  if (in_array($rule[0], $set) and in_array($rule[1], $set)) {
    return true;
  }
}

function isRuleInComplaince($rule, $set) {

  $firstKey = array_search($rule[0], $set);
  $secondKey = array_search($rule[1], $set);

  if ($firstKey < $secondKey) {
    return true;
  } else {
  }
}

function checkSet($set, $rules) {

  colorLog('Working on set ' . $set . PHP_EOL);
  //$activeSet = explode(',', $set);
  $setArry = explode(',', $set);

  for ($i = 0; $i < sizeof($rules); $i++) {

    colorLog('Checking rule ' . $rules[$i] . PHP_EOL, 'w');

    $ruleArray = explode('|', $rules[$i]);


    if (isRuleInSet($ruleArray, $setArry)) {
      echo 'Rule applies to set' . PHP_EOL;
      if (!isRuleInComplaince($ruleArray, $setArry)) {
        colorLog('Rule is broken by set' . PHP_EOL, 'r');
        $re = reOrder($ruleArray, $setArry);
        $setArry = $re;
        print_r($re);
        $i = 0;
      }
    }
  }

  return $re;
}

function main($data) {

  $data = loadPuzzleDay5('dayFive');

  $useTestData = false;

  if ($useTestData) {
    $puzzleData = $data[1];
  } else {
    $puzzleData = $data[0];
  }

  $puzzleRules = explode(PHP_EOL, $puzzleData[0]);
  $puzzleSets = explode(PHP_EOL, $puzzleData[1]);

  $badSets = getGoodSets($puzzleRules, $puzzleSets);
  colorLog('The following sets are non compliant with the ruels ' . PHP_EOL);
  print_r($badSets);

  $goodSets = [];

  foreach ($badSets as $set) {

    $goodSets[] = checkSet($set, $puzzleRules);
    //print_r($activeSet);
  }
  colorLog('The following sets are fixed ' . PHP_EOL);
  print_r($goodSets);

  addGoodSets($goodSets);

  //$set = explode(',', $badSets);
  // foreach ($puzzleRules as $rule) {
  //   foreach ($badSets as $set) {
  //     echo 'Checking rule ' . $rule . ' against set ' . $set . PHP_EOL;
  //     $ra = explode('|', $rule);
  //     $sa = explode(',', $set);

  //     if (isRuleInSet($ra, $sa)) {
  //       echo 'Rule applies to set' . PHP_EOL;
  //       if (!isRuleInComplaince($ra, $sa)) {
  //         colorLog('Rule is broken by set' . PHP_EOL, 'r');
  //         $re = reOrder($ra, $sa);
  //         print_r($re);
  //       }
  //     }

  //     //print_r($ra);

  //     //echo 'blah';
  //     //
  //     break;
  //   }
  //}

  //addGoodSets($setsToAdd);
}
