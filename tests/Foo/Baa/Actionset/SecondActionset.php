<?php

namespace Foo\Baa\Actionset;

use Foo\Baa\Action\FifthAction;
use Foo\Baa\Action\SixthsAction;
use PHP\Manipulator\Actionset;

class SecondActionset extends Actionset
{
    public function getActions()
    {
        return [
            new FifthAction(['baa' => 'foo']),
            new SixthsAction(['foo' => 'baa']),
        ];
    }
}
