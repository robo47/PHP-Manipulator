#!/usr/bin/env php
<?php
if (strpos('@php_bin@', '@php_bin') === 0) {
    set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());
}

require 'PHP/Manipulator/Cli.php';

PHP\Manipulator\Cli::run();