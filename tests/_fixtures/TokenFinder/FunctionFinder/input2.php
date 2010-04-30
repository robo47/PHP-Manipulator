<?php
/**
 *
 */
function foo(Iterator $param)
{
    if($param->valid()) {
        echo 'valid';
    } else {
        echo 'invalid';
    }
}
// Foo
echo foo();