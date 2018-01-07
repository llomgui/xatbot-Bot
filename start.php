<?php

require_once 'vendor/autoload.php';

use xatbot\Server;
use xatbot\Models;

$servers = [['name' =>'Sun']];

foreach ($servers as $server) {
    $server = new Server($server['name']);
    $server->handle();
    sleep(5);
}
