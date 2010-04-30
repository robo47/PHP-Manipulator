<?php

namespace Baa\Foo\Actionset;

use PHP\Manipulator\Actionset;
use PHP\Manipulator\Action;
use Baa\Foo\Action\FifthAction;
use Baa\Foo\Action\SixthsAction;

class FirstActionset
extends Actionset
{
    
    public function getActions()
    {
        return array(
            new SixthsAction(array('foo' => 'baa')),
            new FifthAction(array('baa' => 'foo')),
        );
    }
}