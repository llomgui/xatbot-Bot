# xatbot - xatBot [![Build Status](https://travis-ci.org/llomgui/xatbot-Bot.svg?branch=master)](https://travis-ci.org/llomgui/xatbot-Bot)

## What do i need to run it?

- PHP7.1
- A xat account to use it as bot

## How does it work?

First, edit config.json.example with your data, rename it to config.json.
If you want to add modules or commands, don't forget to update modules.json or commands.json.

This project is linked to [xatbot Website](https://github.com/llomgui/xatbot-Website)
You need to clone this projet to make it work (It has the database).

Once everything is cloned, you can launch bots servers with: php start.php

Docker:

- docker run -it --rm -v $(pwd):/op php:7.1-alpine sh
- cd op
- docker-php-ext-install sockets
- php start.php

Docker-compose:

- docker-compose run --rm php

## I want to contribute, how do I?

Make your tests on your computer, then send a pull request.

This has been tested on Windows PC and doesn't seem to work as intended.
