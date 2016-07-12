# OceanProject - xatBot

## What do i need to run it?

- PHP7
- A xat account to use it as bot

## How does it work?

First, edit config.json.example with your data, rename it to config.json.
If you want to add modules or commands, don't forget to update modules.json or commands.json.

To run it: php dev.php

Or better :

- docker run -it --rm -v $(pwd):/scripts php:7-alpine sh
- cd scripts
- docker-php-ext-install sockets
- php dev.php

Or install docker-compose

## I want to contribute, how do I?

Make your tests on your computer, then send a pull request.

This has been tested on Windows PC and dosen't seem to work as intended