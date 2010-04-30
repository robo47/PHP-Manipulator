<?php
/**
 *
 */
class Foo
{
    public static function baa(Iterator $param)
    {
        if($param->valid()) {
            echo 'valid';
        } else {
            echo 'invalid';
        }
    }
}
// Foo
echo Foo::baa();