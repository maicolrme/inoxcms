<?php

use Illuminate\Http\Request;

define('INOX_START', microtime(true));

require __DIR__ . '/core/vendor/autoload.php';

$app = require_once __DIR__ . '/core/bootstrap/app.php';

$app->handleRequest(Request::capture());
