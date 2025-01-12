<?php

declare(strict_types=1);

use GL\VectorGraphics\VGColor;
use GL\VectorGraphics\VGContext;

require_once('AdventOfCode.php');

class AdventEight extends AdventOfCode {

  public string $day = 'Eight';
  public bool $test  = true;

  public array $drawState = [];
  public array $gridState = [];
  public array $textColors = [
    '-' => 'r',
    '^' => 'g',
    '>' => 'g',
    'v' => 'g',
    '<' => 'g',
    '.' => 'w',
    '#' => 'c',
  ];



  private function spiral() {
  }

  private function processLoop() {

    $row = 0;

    foreach ($this->activeData as $line) {

      $column = 0;
      $line = str_split($line);
      foreach ($line as $char) {

        if ($char === '-') {
          $color = 'r';
        } else if ($char === '.') {
          $color = 'c';
        } else {
          $color = 'g';
        }

        $this->changeCharColor([$row, $column], $color);
        $this->writeToScreen();
        $column++;
      }
      $row++;
    }
  }

  private function initGraphics() {

    $row = 0;

    foreach ($this->activeData as $line) {

      $column = 0;
      $line = str_split($line);
      foreach ($line as $char) {

        if ($char === '-') {
          $color = 'r';
        } else if ($char === '.') {
          $color = 'c';
        } else {
          $color = 'g';
        }

        // $this->changeCharColor([$row, $column], $color);
        // $this->writeToScreen();
        // glfwGetWindowContentScale($this->window, $scaleX, $scaleY);
        // echo $scaleX;

        $this->vg->beginPath();
        $this->vg->roundedRect($column, $row, 48, 48, 3);

        if ($char === '-') {
          $this->vg->fillColor(VGColor::red()->lighten(0.2));
        } else if ($char === '.') {
          $this->vg->fillColor(VGColor::blue()->lighten(0.2));
        } else {
          $this->vg->fillColor(VGColor::green()->lighten(0.2));
        }

        $this->vg->fill();

        $column = $column + 50;
      }
      $row = $row + 50;
    }
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
        $this->gridState[$key]['size'] = $squareSize;

        $column++;
        $x = $x + $pointSize;
      }
      $row++;
      $y = $y + $pointSize;
    }
  }

  private function iterateGrid() {

    foreach ($this->gridState as $key => $index) {

      $this->gridState[$key]['char'] = 'O';
      $this->drawGrid();
    }
  }

  private function drawGrid() {

    $this->vg->beginFrame($this->windowSize[1], $this->windowSize[1], 1);

    foreach ($this->gridState as $index) {
      $this->vg->beginPath();
      $this->vg->roundedRect($index['x'], $index['y'], $index['size'], $index['size'], 3);

      if ($index['char'] === '-') {
        $this->vg->fillColor(VGColor::red()->lighten(0.2));
        $this->vg->fill();
      } else if ($index['char'] === '.') {
        $this->vg->fillColor(VGColor::blue()->lighten(0.2));
        $this->vg->fill();
      } else {
        $this->vg->fillColor(VGColor::green()->lighten(0.2));
        $this->vg->fill();

        // draw text
        $this->vg->beginPath();
        $this->vg->fontSize(20);
        $this->vg->fillColor(VGColor::black());
        $this->vg->text($index['x'] + ($index['size'] / 4), $index['y'] + ($index['size'] - 2), $index['char']);
      }
    }



    $this->vg->endFrame();
    glfwSwapBuffers($this->window);
    glfwPollEvents();
  }

  public function graphicsLoop() {

    while (!glfwWindowShouldClose($this->window)) {
      glClearColor(0, 0, 0, 1);
      glClear(GL_COLOR_BUFFER_BIT);



      // DRAW STUFF HERE...

      $this->drawGrid();
      //$this->iterateGrid();




      // end the frame will dispatch all the draw commands to the GPU


      // swap the windows framebuffer and
      // poll queued window events.

      // $this->vg->beginFrame($this->windowSize[1], $this->windowSize[1], 1);
      // $this->vg->beginPath();
      // $this->vg->roundedRect(100, 100, 48, 48, 3);
      // $this->vg->fillColor(VGColor::red()->lighten(0.2));

      // $this->vg->endFrame();
      // glfwSwapBuffers($this->window);
      // glfwPollEvents();

      // sleep(10);
      // break;

      // $this->vg->beginFrame($this->windowSize[1], $this->windowSize[1], 1);

      // // DRAW STUFF HERE...


      // //$this->initGraphics();

      // $this->vg->beginPath();
      // $this->vg->roundedRect(100, 100, 74, 75, 3);
      // $this->vg->fillColor(VGColor::red()->lighten(0.2));
      // $this->vg->fill();


      // $this->vg->endFrame();
      // glfwSwapBuffers($this->window);
      // glfwPollEvents();

      // sleep(1);
    }
  }

  public function partOne(): void {

    // $this->initDisplay();
    // $this->writeToScreen();
    // $this->processLoop();

    $this->initGrid([0, 0]);
    //print_r($this->gridState);

    $this->graphicsLoop();

    glfwDestroyWindow($this->window);
    glfwTerminate();
  }

  public function partTwo() {
  }
}
