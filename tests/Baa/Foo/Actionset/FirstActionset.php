<?php

namespace Baa\Foo\Actionset;

use PHP\Manipulator\Actionset;
use PHP\Manipulator\Action;
use Baa\Foo\Action\ThirdAction;
use Baa\Foo\Action\FourthAction;

class FirstActionset
extends Actionset
{
    public function getActions()
    {
        return array(
            new ThirdAction(array('blub' => 'bla')),
            new FourthAction(array('bla' => 'blub')),
        );
    }
}