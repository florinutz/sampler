<?php
require_once __DIR__ . '/vendor/autoload.php';

const SIZE = 5;

$f = fopen('php://stdin', 'r');

$sample = new \Sampler\Sample(SIZE, new \Sampler\Input\StreamInput($f, 1024));

echo $sample, PHP_EOL;

$memory = new \Sampler\MemoryUsage();
echo sprintf('Maximum allocated memory during runtime: %s', $memory), PHP_EOL;
