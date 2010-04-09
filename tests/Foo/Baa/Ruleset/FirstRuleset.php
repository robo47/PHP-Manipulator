<?php

namespace Foo\Baa\Ruleset;

use PHP\Manipulator\IRuleset;
use PHP\Manipulator\Rule;
use Foo\Baa\Rule\ThirdRule;
use Foo\Baa\Rule\FourthRule;

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