<?php

/**
 * This is the main class for solving Advent of Code puzzles
 */

declare(strict_types=1);

use GL\VectorGraphics\VGColor;
use GL\VectorGraphics\VGContext;
use GL\VectorGraphics\VGAlign;

class AdventOfCode {

  // puzzle related
  public string $day;
  public array  $data;
  public array  $display;
  public array  $activeData;
  public bool   $test;

  // terminal display related
  public array $textColors;

  // openGL related
  public bool $showDisplay = true;
  public bool $graphics = true;
  public array $windowSize = [1920, 1080];
  public bool $didClick = false;
  public array $drawables = [];
  public int $frameWait = 0;
  public $window;
  public $vg;
  public array $colors = [];

  public function __construct() {
    $this->load();

    if ($this->graphics) {
      if (!glfwInit()) {
        throw new Exception('GLFW could not be initialized!');
      }

      // make sure to set the GLFW context version to the same 
      // version the GLFW extension has been compiled with, default 4.1
      glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 4);
      glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 1);
      glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);
      glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

      // Create a window 
      if (!$this->window = glfwCreateWindow($this->windowSize[0], $this->windowSize[0], "Tim's Great AOC Visualization")) {
        throw new Exception('OS Window could not be initialized!');
      }

      glfwMakeContextCurrent($this->window);
      glfwSwapInterval(1);

      // initalize the a vector graphics context
      $this->vg = new VGContext(VGContext::ANTIALIAS);
      $fontHandle = $this->vg->createFont('courier', __DIR__ . '/data/Courier New.ttf');
      $this->vg->fontFaceId($fontHandle);
      $this->vg->textAlign(VGAlign::CENTER | VGAlign::MIDDLE);

      // $this->graphicsLoop();

      // glfwDestroyWindow($this->window);
      // glfwTerminate();

    }
  }

  public function setColors($color) {

    if ($color === 'red') {
      $this->vg->fillColor(VGColor::red()->lighten(0.2));
    } else if ($color === 'blue') {
      $this->vg->fillColor(VGColor::blue()->lighten(0.2));
    } else if ($color === 'green') {
      $this->vg->fillColor(VGColor::green()->lighten(0.2));
    } else if ($color === 'yellow') {
      $this->vg->fillColor(VGColor::yellow()->lighten(0.2));
    } else if ($color === 'cyan') {
      $this->vg->fillColor(VGColor::cyan()->lighten(0.2));
    } else if ($color === 'magenta') {
      $this->vg->fillColor(VGColor::magenta()->lighten(0.2));
    } else if ($color === 'orange') {
      $this->vg->fillColor(VGColor::orange()->lighten(0.2));
    } else if ($color === 'pink') {
      $this->vg->fillColor(VGColor::pink()->lighten(0.2));
    } else if ($color === 'purple') {
      $this->vg->fillColor(VGColor::purple()->lighten(0.2));
    } else if ($color === 'brown') {
      $this->vg->fillColor(VGColor::brown()->lighten(0.2));
    } else if ($color === 'gray') {
      $this->vg->fillColor(VGColor::gray()->lighten(0.2));
    } else if ($color === 'darkGray') {
      $this->vg->fillColor(VGColor::darkGray()->lighten(0.2));
    } else if ($color === 'lightGray') {
      $this->vg->fillColor(VGColor::lightGray()->lighten(0.2));
    } else if ($color === 'white') {
      $this->vg->fillColor(VGColor::white()->lighten(0.2));
    } else if ($color === 'black') {
      $this->vg->fillColor(VGColor::black()->lighten(0.2));
    }
  }

  // overwrite this function with graphics loop for child process
  // public function graphicsLoop() {

  //   // Main Loop
  //   while (!glfwWindowShouldClose($this->window)) {
  //     glClearColor(0, 0, 0, 1);
  //     glClear(GL_COLOR_BUFFER_BIT);

  //     // start a new vector graphics frame
  //     $this->vg->beginFrame(800, 600, 1);
  //     $this->vg->beginPath();
  //     $this->vg->rect(10, 10, 100, 100);
  //     $this->vg->fillColor(VGColor::red());
  //     $this->vg->fill();
  //     // DRAW STUFF HERE...

  //     // end the frame will dispatch all the draw commands to the GPU
  //     $this->vg->endFrame();

  //     // swap the windows framebuffer and
  //     // poll queued window events.
  //     glfwSwapBuffers($this->window);
  //     glfwPollEvents();
  //   }
  // }

  /**
   * starts new frame, calls all drawable methods and draws frame
   */

  public function draw() {

    glClearColor(0, 0, 0, 1);
    glClear(GL_COLOR_BUFFER_BIT);

    $this->vg->beginFrame($this->windowSize[0], $this->windowSize[0], 1);

    foreach ($this->drawables as $comp) {
      call_user_func(array($this, $comp));
    }

    $this->vg->endFrame();
    glfwSwapBuffers($this->window);
    glfwPollEvents();

    usleep($this->frameWait);
  }

  /**
   * main graphics loop
   */

  public function graphicsLoop() {

    $this->didClick = false;
    glfwSetMouseButtonCallback($this->window, function ($button, $action, $mods) use (&$didClick) {
      if ($button == GLFW_MOUSE_BUTTON_LEFT && $action == GLFW_PRESS) {
        $this->didClick = true;
      }
    });

    while (!glfwWindowShouldClose($this->window)) {
      $this->draw();
    }
    glfwDestroyWindow($this->window);
    glfwTerminate();
  }

  public function load(): void {

    $this->loadPuzzleData();

    if ($this->test === true) {
      $this->activeData = $this->data[1];
    } else {
      $this->activeData = $this->data[0];
    }
  }

  private function loadTextFile(string $file): array {

    $puzzleOpen = fopen($file, "r") or die("Unable to open file!");
    $puzzleText = fread($puzzleOpen, filesize($file));
    return explode(PHP_EOL, $puzzleText);
  }

  public function loadPuzzleData(): void {

    $liveData = $this->loadTextFile('data/day' . $this->day . '.txt');
    $testData = $this->loadTextFile('data/day' . $this->day . 'Test.txt');

    $this->data = [$liveData, $testData];
  }

  /**
   * prints out the current puzzle data
   */

  public function showActiveData(): void {

    print_r($this->data);
  }

  /**
   * Color Log - function for echoing text of different colors to the terminal
   */

  public function clog(string $text, string $type): void {

    $colors = [
      'r' => 31, //red
      'b' => 34, //blue
      'g' => 32, //green
      'w' => 97, //white
      'c' => 36  //cyan
    ];

    $color = $colors[$type] ?? 0;
    echo "\033[" . $color . "m" . $text . "\033[0m";
  }

  /**
   * echo out display data to terminal
   */

  private function display(): void {

    foreach ($this->display as $row) {

      $rowText = '';

      foreach ($row as $col) {
        $rowText .= $this->clog($col[0], $col[1]);
      }
      echo $rowText;
      echo PHP_EOL;
    }
  }

  /**
   * clears the terminal screen so that you can write a new frame
   */

  private function clearTerminal(): void {
    echo "\e[H";
  }

  /**
   * display loop with delay
   */

  public function writeToScreen(int $delay = 1): void {

    if ($this->showDisplay) {
      $this->clearTerminal();
      $this->display();
      usleep($delay);
    }
  }

  public function getCharFromIndex(array $index): string {

    return $this->display[$index[0]][$index[1]][0];
  }

  public function changeCharAtIndex($index, $char) {

    $this->display[$index[0]][$index[1]][0] = $char;
  }

  public function changeCharColor($index, $color) {

    $this->display[$index[0]][$index[1]][1] = $color;
  }

  public function resetDisplay() {

    $this->display = [];
  }

  public function initDisplay(): void {

    $row = 0;

    foreach ($this->activeData as $line) {

      $column = 0;
      $line = str_split($line);
      foreach ($line as $char) {
        if (!isset($this->display[$row][$column])) {
          $color = $this->textColors[$char] ?? 'w';
          $this->display[$row][$column] = [$char, $color];
        }
        $column++;
      }
      $row++;
    }
  }
}
