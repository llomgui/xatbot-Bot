<?php

$servers = [
    ['name' => 'Sun'],
    ['name' => 'Mercury'],
    ['name' => 'Venus'],
    ['name' => 'Earth'],
    ['name' => 'Mars'],
    ['name' => 'Jupiter'],
    ['name' => 'Saturn'],
    ['name' => 'Uranus'],
    ['name' => 'Neptune'],
    ['name' => 'Pluto']
];

foreach ($servers as $server) {
    forkOff('system', ['php slave.php ' . $server['name']]);
}

function forkOff($lambda, $args)
{
    $pid = pcntl_fork();
    if ($pid === -1) {
        die('Forking failed.');
    }

    if ($pid === 0) {
        while (true) {
            $pid = pcntl_fork();

            if ($pid === -1) {
                die('Forking failed.');
            }

            if ($pid === 0) {
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