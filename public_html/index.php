<?php

use Framework\AppKernel;
use Framework\Http\Request;

define('LOG_DIR', __DIR__ . '/../log');
define('CONFIG_DIR', __DIR__ . '/../config');
define('VIEW_DIR', __DIR__ . '/../src/View');

require __DIR__.'/../vendor/autoload.php';

$kernel = new AppKernel();
$request = Request::createFromGlobals();
$kernel->handle($request);