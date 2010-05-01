<?php
/**
 *
 */
class Foo
{
    function blub(Foo $param) {

    }

    function baa(Foo $param)
    {
        if($param->valid()) {
            echo 'valid';
        } else {
            echo 'invalid';
        }
    }

    function blubbla(Foo $param) {

    }
}