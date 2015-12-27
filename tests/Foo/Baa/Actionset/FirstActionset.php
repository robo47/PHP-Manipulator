<?php

namespace Foo\Baa\Actionset;

use Foo\Baa\Action\FourthAction;
use Foo\Baa\Action\ThirdAction;
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
