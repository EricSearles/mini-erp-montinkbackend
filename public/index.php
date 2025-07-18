<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Connection;

$db = Connection::connect();

require __DIR__ . '/../routes/web.php';