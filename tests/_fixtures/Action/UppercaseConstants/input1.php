<?php

class Blubber
{
    const foo = 'baa';

    public function test()
    {
        echo self::foo;
    }

    public static function foo()
    {

    }
}

Blubber::foo();