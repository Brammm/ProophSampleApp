<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Todo\Api\Application;

(new Dotenv(__DIR__ . '/..'))->load();

$app = new Application();
$app->run();
