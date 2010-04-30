<?php

switch ($foo) {
    case 'baa':
        dooBaa();
        break;
    case 'foo':
        doFoo();
        break;
    default:
        if (is_int($foo)) {
            doSomethingElse();
        }
        break;
}

function dooFoo()
{
}

function dooBaa()
{
}

function doSomethingElse()
{
}
