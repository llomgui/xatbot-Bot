<?php

// load composer autoload database
require_once 'vendor/autoload.php';
require_once 'classes/database.php';

function forkOff($lambda, $args)
{
    $pid = pcntl_fork();
    if ($pid === -1) {
        die('Forking failed.');
    }

    if ($pid === 0) {
        while(true) {
            $pid = pcntl_fork();

            if ($pid === -1) { // Error
                die('Forking failed.');
            }

            if ($pid === 0) { // Son
                exit(call_user_func_array($lambda, $args));
            }

            $ret = pcntl_waitpid($pid, $status);
            if ($ret !== $pid) {
                exit('Should not happen.');
            }
        }
    }
    return;
}

function startServer($name)
{
    $main = ['php server.php ' . $name . ' >> logs/' . $name . '.log 2>> logs/' . $name . '.err.log'];

    forkOff('system', $main);
    echo '.:: Server ' . $name . ' ready ::.' . PHP_EOL;
}

$servers = [['name' => 'Saturn']];/*Server::all()->toArray();*/

for ($i = 0; $i < sizeof($servers); $i++) {
    startServer($servers[$i]['name']);
    sleep(5);
}

echo 'All servers are ready to serve.' . PHP_EOL;
