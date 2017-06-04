<?php

require_once 'vendor/autoload.php';

use OceanProject\Server;

$server = new Server($argv[1]);

$server->handle();
