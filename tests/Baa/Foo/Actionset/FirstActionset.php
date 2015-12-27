<?php

namespace Baa\Foo\Actionset;

use Baa\Foo\Action\FourthAction;
use Baa\Foo\Action\ThirdAction;
use PHP\Manipulator\Actionset;

class FirstActionset extends Actionset
{
    public function getActions()
    {
        return [
            new ThirdAction(['blub' => 'bla']),
            new FourthAction(['bla' => 'blub']),
        ];
    }
}
