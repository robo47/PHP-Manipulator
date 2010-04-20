<?php

namespace Foo\Baa\Ruleset;

use PHP\Manipulator\Ruleset;
use PHP\Manipulator\Rule;
use Foo\Baa\Rule\FifthRule;
use Foo\Baa\Rule\SixthsRule;

class SecondRuleset
extends Ruleset
{

    public function getRules()
    {
        return array(
            new FifthRule(array('baa' => 'foo')),
            new SixthsRule(array('foo' => 'baa')),
        );
    }
}