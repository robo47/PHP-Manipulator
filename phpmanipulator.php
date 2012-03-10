#!/usr/bin/env php
<?php

use PHP\Manipulator\Cli;

require_once __DIR__ . '/vendor/.composer/autoload.php';

// Cli
$cli = new Cli();
$cli->run();