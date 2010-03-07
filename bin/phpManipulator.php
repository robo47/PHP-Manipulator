#!/usr/bin/env php
<?php
if (strpos('@php_bin@', '@php_bin') === 0) {
    set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
}

require 'PHP/Formatter/Cli.php';

PHP_Formatter_Cli::run();