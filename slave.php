<?php

require_once 'vendor/autoload.php';

use xatbot\Server;
use xatbot\Models;

$server = new Server($argv[1]);
$server->handle();