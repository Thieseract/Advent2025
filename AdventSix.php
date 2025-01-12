<?php

declare(strict_types=1);

ini_set('memory_limit', '24G');

require_once('AdventOfCode.php');

class AdventSix extends AdventOfCode {

  public string $day = 'Six';
  public bool $test  = true;
  public array $active;
  public string $direction = '^';
  public int $moveCount = 0;
  public int $locationCount = 0;
  public int $loopsFound = 0;
  public array $path = [];
  public array $checkPath = [];
  public array $displayLoops = [];
  public array $uniqueKeys = [];
  public int $writeDelay = 100000;
  public array $textColors = [
    '*' => 'r',
    '^' => 'g',
    '>' => 'g',
    'v' => 'g',
    '<' => 'g',
    '.' => 'w',
    '#' => 'c',
  ];

  public array $rightTurn = [
    '^' => '>',
    '>' => 'v',
    'v' => '<',
    '<' => '^',
  ];

  public function __construct() {
    $this->load();
  }

  private function getNextIndex(): array {

    $direction = $this->direction;
    $row = $this->active[0];
    $column = $this->active[1];

    if ($direction === '^') {
      $spotToCheck = [$row - 1, $column];
    } else if ($direction === 'v') {
      $spotToCheck = [$row + 1, $column];
    } else if ($direction === '>') {
      $spotToCheck = [$row, $column + 1];
    } else if ($direction === '<') {
      $spotToCheck = [$row, $column - 1];
    }

    return $spotToCheck;
  }

  private function getStartingIndex() {

    $row = 0;
    foreach ($this->activeData as $line) {
      for ($i = 0; $i < mb_strlen($line); $i++) {
        $char = mb_substr($line, $i, 1);
        if ($char === '^') {
          $this->active = [$row, $i];
        }
      }
      $row++;
    }
  }

  private function logPath(array $index): void {

    $this->path[] = $index;
  }

  private function canMoveTo(array $index): bool {

    if ($this->getCharFromIndex($index) === '#') {
      return false;
    } else {
      return true;
    }
  }

  private function processingLoop(bool $log): void {

    while (true) {

      $next = $this->getNextIndex();

      // check for boundary
      if ($this->getCharFromIndex($next) === '*') {
        // change last visited index to X
        $this->changeCharAtIndex($this->active, 'X');
        $this->changeCharColor($this->active, 'b');
        $this->writeToScreen($this->writeDelay);
        break;
      }

      // check for loops in the path
      if (in_array([$next, $this->direction], $this->checkPath)) {
        $this->loopsFound++;
        $this->displayLoops[] = $this->display;
        break;
      }

      if ($this->canMoveTo($next)) {

        $this->moveCount++;

        // don't log the path when checking obstacle solutions
        if ($log) {
          $this->logPath($next);
        }

        $this->checkPath[] = [$next, $this->direction];

        //new cursor
        $this->changeCharAtIndex($next, $this->direction);
        $this->changeCharColor($next, 'g');

        //visited index
        $this->changeCharAtIndex($this->active, 'X');
        $this->changeCharColor($this->active, 'b');
        $this->active = $next;
        $this->writeToScreen($this->writeDelay);
      } else {
        //turn right
        $this->direction = $this->rightTurn[$this->direction];
      }
    }
  }

  private function countUniqueLocations() {

    foreach ($this->display as $line) {
      foreach ($line as $index) {
        if ($index[0] === 'X') {
          $this->locationCount++;
        }
      }
    }
  }

  private function obstacleTestingLoop($obstacle) {


    $this->resetDisplay();
    $this->initDisplay();
    $this->changeCharAtIndex($obstacle, '#');
    $this->changeCharColor($obstacle, 'g');
    $this->writeToScreen($this->writeDelay);

    $this->processingLoop(false);
  }

  private function showArraySize() {

    echo 'Display - ' . sizeof($this->display) . PHP_EOL;
    echo 'Path - ' . sizeof($this->path) . PHP_EOL;
    echo 'Check Path - ' . sizeof($this->checkPath) . PHP_EOL;
    echo 'Loops - ' . sizeof($this->displayLoops) . PHP_EOL;
    echo 'Keys - ' . sizeof($this->uniqueKeys) . PHP_EOL;
  }

  public function partOne(): void {

    //$this->showDisplay = false;

    $this->initDisplay();
    $this->writeToScreen();

    $this->getStartingIndex();

    $this->processingLoop(true);

    $this->countUniqueLocations();

    echo $this->locationCount;
  }

  public function partTwo() {

    //$this->showDisplay = false;

    $this->initDisplay();
    $this->writeToScreen();

    $this->getStartingIndex();

    $this->processingLoop(true);

    foreach ($this->path as $index) {

      $key = $index[0] . '|' . $index[1];

      if (in_array($key, $this->uniqueKeys)) {
        continue;
      } else {
        $this->uniqueKeys[] = $key;
      }

      $this->getStartingIndex();
      $this->direction = '^';
      $this->checkPath = [];
      $this->obstacleTestingLoop($index);

      $this->showArraySize();
    }

    // foreach ($this->displayLoops as $loop) {
    //   $this->display = $loop;
    //   $this->writeToScreen();
    //   sleep(5);
    // }

    echo $this->loopsFound;
  }
}
