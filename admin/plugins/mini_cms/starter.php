<?php

declare(strict_types=1);

$op = $op ?? '';

if ($op === 'add') {
    // Custom operations during installation
} elseif ($op === 'rm') {
    // Custom operations during removal
}

require __DIR__ . '/config.php';
