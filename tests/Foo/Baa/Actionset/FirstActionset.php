<?php

namespace Foo\Baa\Actionset;

use PHP\Manipulator\Actionset;
use PHP\Manipulator\Action;
use Foo\Baa\Action\ThirdAction;
use Foo\Baa\Action\FourthAction;

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