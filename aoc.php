<?php

declare(strict_types=1);

require_once('setup.php');

$activePuzzle = ['Two', 'Two'];
$useTestData = false;

$puzzleData = loadPuzzle($activePuzzle, $useTestData);

main($puzzleData);
