<?php

namespace Foo\Baa\Actionset;

use PHP\Manipulator\Actionset;
use PHP\Manipulator\Action;
use Foo\Baa\Action\FifthAction;
use Foo\Baa\Action\SixthsAction;

class SecondActionset
extends Actionset
{
    public function getActions()
    {
        return array(
            new FifthAction(array('baa' => 'foo')),
            new SixthsAction(array('foo' => 'baa')),
        );
    }
}