<?php

namespace Foo\Baa;

use Foo\Blub;

use Foo\ Bla,
    Foo \Blablub;

class Blubber
{
    const FOO = 'baa';

    public function test()
    {
        echo self :: FOO;
    }
}