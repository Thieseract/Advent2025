<?php

declare(strict_types=1);

use GL\Math\Vec2;
use GL\VectorGraphics\VGColor;
use GL\VectorGraphics\VGContext;

require_once('AdventOfCode.php');

class AdventNine extends AdventOfCode {

  public string $day = 'Nine';
  public bool $test  = false;
  public int $frameWait = 100000;
  private string $solution = '';
  public int $finishCount = 0;

  private string $part;
  public array $gridState = [];
  private array $blockArray = [];
  public array $colorMap = [
    '0' => 'green',
    '1' => 'green',
    '2' => 'green',
    '3' => 'green',
    '4' => 'green',
    '5' => 'green',
    '6' => 'green',
    '7' => 'green',
    '8' => 'green',
    '9' => 'green',
    '.' => 'red'
  ];

  private function findNodesToSwap() {

    $backCheck = array_reverse($this->gridState);

    foreach ($backCheck as $key => $back) {
      if ($back['char'] === '.') {
        continue;
      } else {
        $back = $key;
        break;
      }
    }

    foreach ($this->gridState as $key => $front) {
      if ($front['char'] === '.') {
        $front = $key;
        break;
      } else {
        continue;
      }
    }

    return [$front, $back];
  }

  private function checkForDone() {

    $changes = 0;
    $prevChar = '';

    foreach ($this->gridState as $box) {
      if ($prevChar === '') {
        $prevChar = $box['char'];
      } else {
        if (($prevChar === '.' and $box['char'] !== '.') or ($prevChar !== '.' and $box['char'] === '.')) {
          $changes++;
          $prevChar = $box['char'];
          //echo $prevChar . '||' . $box['char'];
        }
      }
    }

    return $changes;
  }

  private function swap($swaps) {



    $frontChar = $this->gridState[$swaps[0]]['char'];
    $frontColor = $this->gridState[$swaps[0]]['color'];
    $backChar = $this->gridState[$swaps[1]]['char'];
    $backColor = $this->gridState[$swaps[1]]['color'];

    // if ($frontChar !== '.') {
    // } else {
    $this->gridState[$swaps[0]]['char'] = $backChar;
    $this->gridState[$swaps[1]]['char'] = $frontChar;
    $this->gridState[$swaps[0]]['color'] = $backColor;
    $this->gridState[$swaps[1]]['color'] = $frontColor;
    $this->draw();
    // }
  }

  private function solvePartOne() {

    while ($this->checkForDone() !== 1) {
      $swaps = $this->findNodesToSwap();
      $this->swap($swaps);
    }

    $this->addChecksum();
    //print_r(($this->gridState));
  }

  /**
   * call the main solving function when the run button is pushed
   */

  private function buttonPushed() {

    $this->didClick = false;

    if ($this->part === 'One') {
      $this->solvePartOne();
    } else {
      //$this->solvePartTwo();
    }
  }

  /**
   * creates the answer vector
   */

  protected function solution() {

    $this->vg->beginPath();
    $this->vg->fontSize(20);
    $this->vg->fillColor(VGColor::white());
    $this->vg->text(1300, 500, 'Part One Solution: ' . $this->solution);
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

  private function parseLine() {

    $file = true;
    $newString = '';
    $id = 0;

    foreach (str_split($this->activeData[0]) as $char) {
      //echo $char;
      if ($file) {
        for ($i = 0; $i < intval($char); $i++) {
          $newString .= $id;
          $this->blockArray[] = $id;
        }
        $file = false;
        $id++;
      } else {
        for ($i = 0; $i < intval($char); $i++) {
          $newString .= '.';
          $this->blockArray[] = '.';
        }
        $file = true;
      }
    }
    $this->activeData[0] = $newString;
    // print_r($this->blockArray);
  }

  // private function setupFinishCheck() {

  //   foreach ($this->blockArray as $char) {
  //     //echo $char . PHP_EOL;
  //     if ($char !== '.') {
  //       $this->finishCount++;
  //     }
  //   }
  //   // echo 'Number of active files' . $this->finishCount;
  // }

  // private function finishCheck(array $order): bool {


  //   $count = 0;
  //   foreach ($order as $char) {
  //     if ($char === '.') {
  //       break;
  //     } else {
  //       $count++;
  //     }
  //   }

  //   if ($this->finishCount === $count) {
  //     return true;
  //   } else {
  //     return false;
  //   }
  // }

  // private function reOrder() {

  //   $newOrder = $this->blockArray;

  //   for ($i = (sizeof($this->blockArray) - 1); $i >= 0; $i--) {
  //     //echo $this->blockArray[$i];
  //     if ($this->blockArray[$i] === '.') {
  //       continue;
  //     } else {
  //       for ($j = 0; $j < sizeof($newOrder); $j++) {
  //         if ($newOrder[$j] === '.') {
  //           if (!$this->finishCheck($newOrder)) {
  //             // echo 'placing ' . $this->blockArray[$i] . ' at index ' . $j . PHP_EOL;
  //             // echo 'placing . at index ' . $i . PHP_EOL;
  //             $newOrder[$j] = $this->blockArray[$i];
  //             $newOrder[$i] = '.';
  //             // print_r($newOrder);
  //           }
  //           break;
  //         }
  //       }
  //     }
  //   }

  //   $this->blockArray = $newOrder;
  // }

  private function addChecksum() {

    $total = 0;
    $i = 0;

    // for ($i = 0; $i < $this->gridState; $i++) {
    //   $total += $i * $this->gridState[$i];
    // }
    foreach ($this->gridState as $box) {
      if ($box['char'] !== '.') {
        $total += $i * intval($box['char']);
        $i++;
      }
    }
    echo $total;
  }

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

        $this->gridState[$key]['char'] = $char;
        $this->gridState[$key]['x'] = $x;
        $this->gridState[$key]['y'] = $y;
        $this->gridState[$key]['row'] = $row;
        $this->gridState[$key]['col'] = $column;
        $this->gridState[$key]['size'] = $squareSize;

        $this->gridState[$key]['color'] = $this->colorMap[$char];

        $column++;
        $x = $x + $pointSize;
      }
      $row++;
      $y = $y + $pointSize;
    }
  }

  public function problemGrid() {

    foreach ($this->gridState as $index) {
      $this->vg->beginPath();
      $this->vg->roundedRect($index['x'], $index['y'], $index['size'], $index['size'], 3);

      $this->setColors($index['color']);

      $this->vg->fill();

      // if ($index['char'] !== '-' and $index['char'] !== '.') {

      // draw text
      $this->vg->beginPath();
      $this->vg->fontSize(20);
      $this->vg->fillColor(VGColor::black());
      $this->vg->text($index['x'] + ($index['size'] / 4), $index['y'] + ($index['size'] - 4), $index['char']);
      //}
    }
  }

  /**
   * part one calling function, sets up drawables and calls graphics loop
   */

  public function partOne(): void {

    $this->part = 'One';


    $this->parseLine();
    $this->initGrid([0, 50]);

    //print_r($this->gridState);
    // $this->setupFinishCheck();
    // $this->reOrder();
    // $this->addChecksum();

    $this->drawables[] = 'infoPanel';
    $this->drawables[] = 'problemGrid';
    $this->drawables[] = 'solution';
    $this->drawables[] = 'runButton';

    //$this->initGrid([0, 0]);

    $this->graphicsLoop();
  }

  public function partTwo() {

    $this->part = 'Two';

    $this->drawables[] = 'infoPanel';
    //$this->drawables[] = 'grid';
    $this->drawables[] = 'solution';
    $this->drawables[] = 'runButton';

    //$this->initGrid([0, 0]);

    $this->graphicsLoop();
  }
}
