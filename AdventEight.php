<?php

declare(strict_types=1);

use GL\Math\Vec2;
use GL\VectorGraphics\VGColor;
use GL\VectorGraphics\VGContext;

require_once('AdventOfCode.php');

class AdventEight extends AdventOfCode {

  public string $day = 'Eight';
  public bool $test  = false;
  public array $indexToTest = [];
  public array $answersOne = [];
  public int $frameWait = 100000;

  private string $part;



  public array $gridState = [];

  /**
   * call the main solving function when the run button is pushed
   */

  private function buttonPushed() {

    $this->didClick = false;

    if ($this->part === 'One') {
      $this->iterateGrid();
    } else {
      $this->iterateGridTwo();
    }
  }

  /**
   * translates the problem data into more detailed grid data for both solving and display
   */

  private function initGrid(array $start): void {

    $pointSize = 25;
    $squareSize = 24;

    $row = 0;
    $y = $start[1];

    foreach ($this->activeData as $line) {

      $column = 0;
      $x = $start[0];


      $line = str_split($line);
      foreach ($line as $char) {



        $key = $row . '|' . $column;
        $this->gridState[$key] = [];

        if ($char !== '-' and $char !== '.') {
          $this->indexToTest[$char][] = $key;
        }

        $this->gridState[$key]['char'] = $char;
        $this->gridState[$key]['x'] = $x;
        $this->gridState[$key]['y'] = $y;
        $this->gridState[$key]['row'] = $row;
        $this->gridState[$key]['col'] = $column;
        $this->gridState[$key]['size'] = $squareSize;

        if ($char === '-') {
          $this->gridState[$key]['color'] = 'red';
        } else if ($char === '.') {
          $this->gridState[$key]['color'] = 'blue';
        } else {
          $this->gridState[$key]['color'] = 'green';
        }

        $column++;
        $x = $x + $pointSize;
      }
      $row++;
      $y = $y + $pointSize;
    }
  }

  /**
   * finds the antinodes for each pair of points and makes sure they are in the grid
   */

  private function checkForAntiNodes($pointOne, $pointTwo) {

    $x = ($pointOne[0] - $pointTwo[0]) + $pointOne[0];
    $y = ($pointOne[1] - $pointTwo[1]) + $pointOne[1];

    $key = $y . '|' . $x;

    if (isset($this->gridState[$key])) {
      if ($this->gridState[$key]['char'] === '-') {
      } else {
        $this->gridState[$key]['color'] = 'pink';
        $this->draw();

        $this->answersOne[] = $key;
      }
    }
  }

  /**
   * finds the antinodes for each pair of points and makes sure they are in the grid
   */

  private function checkForAntiNodesTwo($pointOne, $pointTwo) {

    $this->answersOne[] = $pointOne[1] . '|' . $pointOne[0];

    $go = true;

    $active = $pointOne;
    $toCheck = $pointTwo;

    while ($go) {
      print_r($active);
      print_r($toCheck);
      $x = $toCheck[0] - ($active[0] - $toCheck[0]);
      $y = $toCheck[1] - ($active[1] - $toCheck[1]);

      $active = $toCheck;
      $toCheck = [$x, $y];

      $key = $y . '|' . $x;
      echo $key . PHP_EOL;

      if (isset($this->gridState[$key])) {
        if ($this->gridState[$key]['char'] === '-') {
        } else {
          $this->gridState[$key]['color'] = 'pink';
          $this->draw();

          $this->answersOne[] = $key;
        }
      } else {
        $go = false;
      }
    }
  }

  /**
   * iterates through relevant points and calls the check function
   */

  private function iterateGrid() {

    foreach ($this->indexToTest as $char) {

      //print_r($char);
      foreach ($char as $base) {
        $this->gridState[$base]['color'] = 'white';
        $this->draw();

        foreach ($char as $arm) {
          if ($base === $arm) {
            continue;
          }
          $this->gridState[$arm]['color'] = 'red';
          $this->draw();

          $this->checkForAntiNodes([$this->gridState[$base]['col'], $this->gridState[$base]['row']], [$this->gridState[$arm]['col'], $this->gridState[$arm]['row']]);
          $this->gridState[$arm]['color'] = 'green';
          $this->draw();
        }
        $this->gridState[$base]['color'] = 'green';
        $this->draw();
      }
    }
  }

  /**
   * iterates through relevant points and calls the check function for part two
   */

  private function iterateGridTwo() {

    foreach ($this->indexToTest as $char) {

      //print_r($char);
      foreach ($char as $base) {
        $this->gridState[$base]['color'] = 'white';
        $this->draw();

        foreach ($char as $arm) {
          if ($base === $arm) {
            continue;
          }
          $this->gridState[$arm]['color'] = 'red';
          $this->draw();

          $this->checkForAntiNodesTwo([$this->gridState[$base]['col'], $this->gridState[$base]['row']], [$this->gridState[$arm]['col'], $this->gridState[$arm]['row']]);
          $this->gridState[$arm]['color'] = 'green';
          $this->draw();
        }
        $this->gridState[$base]['color'] = 'green';
        $this->draw();
      }
    }
  }

  /**
   * creates the grid vectors based on gridState
   */

  protected function grid() {

    foreach ($this->gridState as $index) {
      $this->vg->beginPath();
      $this->vg->roundedRect($index['x'], $index['y'], $index['size'], $index['size'], 3);

      if ($index['color'] === 'red') {
        $this->vg->fillColor(VGColor::red()->lighten(0.2));
      } else if ($index['color'] === 'blue') {
        $this->vg->fillColor(VGColor::blue()->lighten(0.2));
      } else if ($index['color'] === 'green') {
        $this->vg->fillColor(VGColor::green()->lighten(0.2));
      } else if ($index['color'] === 'white') {
        $this->vg->fillColor(VGColor::white()->lighten(0.2));
      } else if ($index['color'] === 'pink') {
        $this->vg->fillColor(VGColor::pink()->lighten(0.2));
      }

      $this->vg->fill();

      if ($index['char'] !== '-' and $index['char'] !== '.') {

        // draw text
        $this->vg->beginPath();
        $this->vg->fontSize(20);
        $this->vg->fillColor(VGColor::black());
        $this->vg->text($index['x'] + ($index['size'] / 4), $index['y'] + ($index['size'] - 4), $index['char']);
      }
    }
  }

  /**
   * creates the answer vector
   */

  protected function solution() {

    $answer = sizeof(array_unique($this->answersOne));
    $this->vg->beginPath();
    $this->vg->fontSize(20);
    $this->vg->fillColor(VGColor::white());
    $this->vg->text(1300, 500, 'Part One Solution: ' . $answer);
  }

  /**
   * creates the info vectors
   */

  protected function infoPanel() {

    $this->vg->beginPath();
    $this->vg->fontSize(20);
    $this->vg->fillColor(VGColor::white());
    $this->vg->text(1300, 100, 'Day ' . $this->day . ' Part ' . $this->part);
  }

  /**
   * creates the run button vectors and click detection
   */

  protected function runButton() {

    //$this->didClick = false;

    //create button
    $this->vg->beginPath();
    $this->vg->roundedRect(1300, 400, 170, 20, 3);
    $this->vg->fillColor(VGColor::gray()->lighten(0.2));
    $this->vg->fill();

    // button label
    $this->vg->beginPath();
    $this->vg->fontSize(20);
    $this->vg->fillColor(VGColor::black());
    $this->vg->text(1300, 400 + 14, 'Run this Bitch');

    //click logic
    glfwGetCursorPos($this->window, $mouseX, $mouseY);
    $mouseVec = new Vec2($mouseX, $mouseY);
    $bitVec = new Vec2(1300, 400);
    if (Vec2::distance($mouseVec, $bitVec) < 50 / 2) {
      $this->vg->fillColor(VGColor::lightGray());
      if ($this->didClick) {
        $this->buttonPushed();
      }
    }
  }

  /**
   * part one calling function, sets up drawables and calls graphics loop
   */

  public function partOne(): void {

    $this->part = 'One';

    $this->drawables[] = 'infoPanel';
    $this->drawables[] = 'grid';
    $this->drawables[] = 'solution';
    $this->drawables[] = 'runButton';

    $this->initGrid([0, 0]);

    $this->graphicsLoop();
  }

  public function partTwo() {

    $this->part = 'Two';

    $this->drawables[] = 'infoPanel';
    $this->drawables[] = 'grid';
    $this->drawables[] = 'solution';
    $this->drawables[] = 'runButton';

    $this->initGrid([0, 0]);

    $this->graphicsLoop();
  }
}
