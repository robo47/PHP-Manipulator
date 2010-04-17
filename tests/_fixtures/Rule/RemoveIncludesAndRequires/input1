<?php

include 'test1.php';
require 'test2.php';
include_once 'test3.php';
require_once 'test4.php';

function requireFile($file, $once = false)
{
    if($once) {
        require_once $file;
    } else {
        require $file;
    }
}

function includeFile($file, $once = false)
{
    if($once) {
        include_once $file;
    } else {
        include $file;
    }
}

class myClass
{
    public function requireFile($file, $once = false)
    {
        if($once) {
            require_once $file;
        } else {
            require $file;
        }
    }

    public function includeFile($file, $once = false)
    {
        if($once) {
            include_once $file;
        } else {
            include $file;
        }
    }
}

function requireFile2($file, $once = false)
{
    if($once) {
        require_once $file;
    } else {
        require $file;
    }
}

function includeFile2($file, $once = false)
{
    if($once) {
        include_once $file;
    } else {
        include $file;
    }
}

include 'test1.php';
require 'test2.php';
include_once 'test3.php';
require_once 'test4.php';