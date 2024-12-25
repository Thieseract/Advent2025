<?php

declare(strict_types=1);

require_once('setup.php');

$activePuzzle = ['Five', 'One'];
$useTestData = false;

$puzzleData = loadPuzzle($activePuzzle, $useTestData);

main($puzzleData);
