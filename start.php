<?php

require_once 'vendor/autoload.php';

use OceanProject\Server;

$servers = ['Saturn'];

for ($i = 0; $i < sizeof($servers); $i++) {
    $server = new Server($servers[$i]);
    $server->handle();
    sleep(5);
}
