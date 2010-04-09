<?php

namespace Baa\Foo\Ruleset;

use PHP\Manipulator\IRuleset;
use PHP\Manipulator\Rule;
use Baa\Foo\Rule\ThirdRule;
use Baa\Foo\Rule\FourthRule;

class FirstRuleset
implements IRuleset
{

    public function getRules()
    {
        return array(
            new ThirdRule(array('blub' => 'bla')),
            new FourthRule(array('bla' => 'blub')),
        );
    }
}