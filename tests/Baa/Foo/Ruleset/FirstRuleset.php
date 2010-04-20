<?php

namespace Baa\Foo\Ruleset;

use PHP\Manipulator\Ruleset;
use PHP\Manipulator\Rule;
use Baa\Foo\Rule\ThirdRule;
use Baa\Foo\Rule\FourthRule;

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