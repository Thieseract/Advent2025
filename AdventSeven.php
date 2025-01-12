<?php

declare(strict_types=1);

require_once('AdventOfCode.php');

class AdventSeven extends AdventOfCode {

  public string $day = 'Seven';
  public bool $test  = false;
  public array $lines = [];
  public array $ops = ['+', '*'];


  public function __construct() {
    $this->load();
  }

  private function iterate(array $contents): array {

    $totals = [];

    $numbs = explode(' ', $contents[1]);
    for ($i = 0; $i < sizeof($numbs); $i++) {
      if ($i === 0) {
        $totals[] = $numbs[$i];
      } else {
        foreach ($totals as $tot) {
          $subs[] = $tot + $numbs[$i];
          $subs[] = $tot * $numbs[$i];
        }
        $totals = $subs;
        $subs = [];
      }
    }

    return $totals;
  }

  private function iterateTwo(array $contents): array {

    $totals = [];

    $numbs = explode(' ', $contents[1]);
    for ($i = 0; $i < sizeof($numbs); $i++) {
      if ($i === 0) {
        $totals[] = $numbs[$i];
      } else {
        foreach ($totals as $tot) {
          $subs[] = $tot + $numbs[$i];
          $subs[] = $tot * $numbs[$i];
          $subs[] = $tot . $numbs[$i];
        }
        $totals = $subs;
        $subs = [];
      }
    }
    //print_r($totals);
    return $totals;
  }

  public function partOne(): void {

    print_r($this->activeData);
    $subTotal = 0;

    foreach ($this->activeData as $line) {
      $contents = explode(': ', $line);
      $iterations = $this->iterate($contents);

      if (in_array($contents[0], $iterations)) {
        $subTotal += $contents[0];
      }
    }
    echo $subTotal;
  }

  public function partTwo() {

    print_r($this->activeData);
    $subTotal = 0;

    foreach ($this->activeData as $line) {
      $contents = explode(': ', $line);
      $iterations = $this->iterateTwo($contents);

      if (in_array($contents[0], $iterations)) {
        $subTotal += $contents[0];
      }
    }
    echo $subTotal;
  }
}
