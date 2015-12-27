<?php

namespace Baa\Foo\Actionset;

use Baa\Foo\Action\FifthAction;
use Baa\Foo\Action\SixthsAction;
use PHP\Manipulator\Actionset;

class SecondActionset extends Actionset
{
    public function getActions()
    {
        return [
            new SixthsAction(['foo' => 'baa']),
            new FifthAction(['baa' => 'foo']),
        ];
    }
}
