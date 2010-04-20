<?php

namespace Foo\Baa\Ruleset;

use PHP\Manipulator\Ruleset;
use PHP\Manipulator\Rule;
use Foo\Baa\Rule\ThirdRule;
use Foo\Baa\Rule\FourthRule;

class FirstRuleset
extends Ruleset
{
    
    public function getRules()
    {
        return array(
            new ThirdRule(array('blub' => 'bla')),
            new FourthRule(array('bla' => 'blub')),
        );
    }
}