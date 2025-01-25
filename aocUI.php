<?php

/**
 * This is the class for the AoC UI
 */

declare(strict_types=1);

require_once('load.php');

use GL\Math\Vec2;
use GL\VectorGraphics\VGColor;
use GL\VectorGraphics\VGContext;
use GL\VectorGraphics\VGAlign;

class aocUI {

  public bool $didClick = false;
  private array $windowSize = [1920, 1080];
  public array $drawables = [];
  private $window;
  private $vg;
  public $dayOne;

  public function __construct() {

    $this->dayOne = new OneA();

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
    if (!$this->window = glfwCreateWindow($this->windowSize[0], $this->windowSize[1], "Tim's Great AOC Visualization")) {
      throw new Exception('OS Window could not be initialized!');
    }

    glfwMakeContextCurrent($this->window);
    glfwSwapInterval(1);

    // initalize the a vector graphics context
    $this->vg = new VGContext(VGContext::ANTIALIAS);
    $fontHandle = $this->vg->createFont('courier', __DIR__ . '/data/Courier New.ttf');
    $this->vg->fontFaceId($fontHandle);
    //$this->vg->textAlign(VGAlign::CENTER | VGAlign::MIDDLE);

    // $this->graphicsLoop();

    // glfwDestroyWindow($this->window);
    // glfwTerminate();
  }

  public function graphicsLoop() {

    $this->didClick = false;
    glfwSetMouseButtonCallback($this->window, function ($button, $action, $mods) use (&$didClick) {
      if ($button == GLFW_MOUSE_BUTTON_LEFT && $action == GLFW_PRESS) {
        $this->didClick = true;
      }
    });
    //$this->introAnimation();

    while (!glfwWindowShouldClose($this->window)) {

      $this->vg->beginPath();
      $this->vg->rect(0, 0, $this->windowSize[0], $this->windowSize[1]);
      $this->vg->fillColor(VGColor::darkGray()->darken(0.2));
      $this->vg->fill();


      foreach ($this->drawables as $comp) {
        call_user_func(array($this, $comp));
      }
      $this->draw();
    }
    glfwDestroyWindow($this->window);
    glfwTerminate();
  }

  public function draw() {

    glClearColor(0, 0, 0, 1);
    glClear(GL_COLOR_BUFFER_BIT);

    $this->vg->beginFrame($this->windowSize[0], $this->windowSize[1], 1);



    $this->vg->endFrame();
    glfwSwapBuffers($this->window);
    glfwPollEvents();

    //usleep($this->frameWait);
  }

  /**
   * creates the info vectors
   */

  protected function infoPanel() {
    $this->vg->beginPath();
    $this->vg->roundedRect(1450, 100, 500, 880, 5);
    $this->vg->fillColor(VGColor::darkGray()->darken(0.3));
    $this->vg->fill();

    $this->vg->beginPath();
    $this->vg->fontSize(20);
    $this->vg->fillColor(VGColor::white());
    $this->vg->text(1450, 100, "info");


    $this->createButton(1475, 125, 75, 20, 'Day 1', 'buttonPushed');
    $this->createButton(1555, 125, 75, 20, 'Day 2', 'buttonPushed');
    $this->createButton(1635, 125, 75, 20, 'Day 3', 'buttonPushed');
    $this->createButton(1715, 125, 75, 20, 'Day 4', 'buttonPushed');
    $this->createButton(1795, 125, 75, 20, 'Day 5', 'buttonPushed');

    $this->createButton(1475, 150, 75, 20, 'Day 6', 'buttonPushed');
    $this->createButton(1555, 150, 75, 20, 'Day 7', 'buttonPushed');
    $this->createButton(1635, 150, 75, 20, 'Day 8', 'buttonPushed');
    $this->createButton(1715, 150, 75, 20, 'Day 9', 'buttonPushed');
    $this->createButton(1795, 150, 75, 20, 'Day 10', 'buttonPushed');

    $this->createButton(1475, 175, 75, 20, 'Day 11', 'buttonPushed');
    $this->createButton(1555, 175, 75, 20, 'Day 12', 'buttonPushed');
    $this->createButton(1635, 175, 75, 20, 'Day 13', 'buttonPushed');
    $this->createButton(1715, 175, 75, 20, 'Day 14', 'buttonPushed');
    $this->createButton(1795, 175, 75, 20, 'Day 15', 'buttonPushed');

    $this->createButton(1475, 200, 75, 20, 'Day 16', 'buttonPushed');
    $this->createButton(1555, 200, 75, 20, 'Day 17', 'buttonPushed');
    $this->createButton(1635, 200, 75, 20, 'Day 18', 'buttonPushed');
    $this->createButton(1715, 200, 75, 20, 'Day 19', 'buttonPushed');
    $this->createButton(1795, 200, 75, 20, 'Day 20', 'buttonPushed');

    $this->createButton(1475, 225, 75, 20, 'Day 21', 'buttonPushed');
    $this->createButton(1555, 225, 75, 20, 'Day 22', 'buttonPushed');
    $this->createButton(1635, 225, 75, 20, 'Day 23', 'buttonPushed');
    $this->createButton(1715, 225, 75, 20, 'Day 24', 'buttonPushed');
    $this->createButton(1795, 225, 75, 20, 'Day 25', 'buttonPushed');

    $this->createButton(1600, 500, 100, 20, 'Part 1', 'buttonPushed');
    $this->createButton(1700, 500, 100, 20, 'Part 1', 'buttonPushed');
    $this->createButton(1600, 500, 100, 20, 'Test', 'buttonPushed');
    $this->createButton(1700, 500, 100, 20, 'Live', 'buttonPushed');

    $this->createButton(1600, 800, 200, 40, 'Run', 'buttonPushed');
  }

  /**
   * creates the puzzleBox vectors
   */

  protected function puzzleBox() {
    $this->vg->beginPath();
    $this->vg->roundedRect(20, 20, 1040, 1040, 5);
    $this->vg->fillColor(VGColor::darkGray()->darken(0.3));
    $this->vg->fill();
  }

  protected function createButton($x, $y, $w, $h, $label, $func) {

    //click logic
    glfwGetCursorPos($this->window, $mouseX, $mouseY);

    $isInside = $mouseX >= $x && $mouseX <= $x + $w &&
      $mouseY >= $y && $mouseY <= $y + $h;

    // button vectors
    $this->vg->beginPath();
    $this->vg->roundedRect($x, $y, $w, $h, 3);
    if ($isInside) {
      $this->vg->fillColor(VGColor::gray()->lighten(0.1));
    } else {
      $this->vg->fillColor(VGColor::gray()->lighten(0.2));
    }
    $this->vg->fill();

    // button label
    $this->vg->beginPath();
    $this->vg->fontSize(20);
    $this->vg->fillColor(VGColor::black());
    $this->vg->text($x, $y + 14, $label);

    if ($isInside) {
      if ($this->didClick) {
        call_user_func([$this, $func]);
      }
    }
  }

  /**
   * creates the run button vectors and click detection
   */

  protected function runButton() {

    //$this->didClick = false;
    $x = 1300;
    $y = 400;
    $width = 170;
    $height = 20;



    //click logic
    glfwGetCursorPos($this->window, $mouseX, $mouseY);

    $isInside = $mouseX >= $x && $mouseX <= $x + $width &&
      $mouseY >= $y && $mouseY <= $y + $height;

    $mouseVec = new Vec2($mouseX, $mouseY);
    $bitVec = new Vec2(1300, 400);
    if ($isInside) {
      // $this->vg->fillColor(VGColor::red());
      if ($this->didClick) {
        $this->buttonPushed();
      }
    }
  }

  /**
   * creates the run button vectors and click detection
   */

  protected function runButton2() {

    //$this->didClick = false;

    //create button
    $this->vg->beginPath();
    $this->vg->roundedRect(1300, 500, 170, 20, 3);
    $this->vg->fillColor(VGColor::gray()->lighten(0.2));
    $this->vg->fill();

    // button label
    $this->vg->beginPath();
    $this->vg->fontSize(20);
    $this->vg->fillColor(VGColor::black());
    $this->vg->text(1300, 500 + 14, 'Run this Bitch');

    //click logic
    glfwGetCursorPos($this->window, $mouseX, $mouseY);
    $mouseVec = new Vec2($mouseX, $mouseY);
    $bitVec = new Vec2(1300, 500);
    if (Vec2::distance($mouseVec, $bitVec) < 50 / 2) {
      $this->vg->fillColor(VGColor::lightGray());
      if ($this->didClick) {
        $this->buttonPushed2();
      }
    }
  }

  protected function introAnimation() {

    //exec('powershell -c (New-Object Media.SoundPlayer "C:\Windows\Media\notify.wav").PlaySync();');
    $tree = '
             ^
            ***
           ***0*
          *******
         **0******
        ********0**
       *****0*******
      **0********0***
     *******0*********
    ****0**********0***
            ---';

    $treeLine = explode(PHP_EOL, $tree);
    $size = 12;


    $y = 100;
    $lineHeight = $size * 4.8;

    $x = 400;
    $charWidth = $size * 2.5;
    $pass = 0;
    while ($pass < 8) {

      $this->vg->beginPath();
      $this->vg->rect(0, 0, $this->windowSize[0], $this->windowSize[1]);
      $this->vg->fillColor(VGColor::darkGray()->darken(0.2));
      $this->vg->fill();
      foreach ($treeLine as $line) {
        $chars = str_split($line);
        foreach ($chars as $char) {

          if ($char === "^") {
            $this->vg->beginPath();
            $this->vg->moveTo($x, $y);
            $this->vg->lineTo($x + 25, $y + 100);
            $this->vg->lineTo($x + 125, $y + 100);
            $this->vg->lineTo($x + 50, $y + 150);
            $this->vg->lineTo($x + 80, $y + 235); #
            $this->vg->lineTo($x, $y + 175);
            $this->vg->lineTo($x - 80, $y + 235);
            $this->vg->lineTo($x - 50, $y + 150);
            $this->vg->lineTo($x - 125, $y + 100);
            $this->vg->lineTo($x - 25, $y + 100);
            $this->vg->closePath();
            $this->vg->fillColor(VGColor::yellow());
            $this->vg->fill();
            $y = $y + 200;
          } else {
            $this->vg->beginPath();
            $this->vg->circle($x, $y, $size);

            if ($char === "*") {
              $this->vg->fillColor(VGColor::green());
            } else if ($char === "0") {
              $this->vg->fillColor(VGColor::random());
            } else if ($char === "-") {
              $this->vg->fillColor(VGColor::brown());
            } else {
              $this->vg->fillColor(VGColor::darkGray()->darken(0.2));
            }

            $this->vg->fill();
          }
          $x = $x + $charWidth;
        }
        $x = 400;
        $y = $y + $lineHeight;
      }

      // no clue why this is need but if I render the title on the first pass it jumps around
      // so this is added so that the first frame doesn't have the text and doesn't wait
      if ($pass !== 0) {
        $this->vg->beginPath();
        $this->vg->fontSize(50);
        $this->vg->fillColor(VGColor::green());
        $this->vg->text(650, 50, "Advent of Code 2024");
        $this->draw();
        $x = 300;
        $y = 100;
        usleep(800000);
        $pass++;
      } else {
        $this->draw();
        $x = 300;
        $y = 100;

        $pass++;
      }
    }
  }

  /**
   * call the main solving function when the run button is pushed
   */

  private function buttonPushed() {

    $this->didClick = false;

    echo 'pushed';
    $this->dayOne->main();
  }

  /**
   * call the main solving function when the run button is pushed
   */

  private function buttonPushed2() {

    $this->didClick = false;

    echo 'pushed2';
  }

  public function run() {
    $this->drawables[] = 'infoPanel';
    $this->drawables[] = 'puzzleBox';
    $this->drawables[] = 'runButton';
    $this->drawables[] = 'runButton2';
    $this->graphicsLoop();
  }
}

$aoc = new aocUI();
$aoc->run();
