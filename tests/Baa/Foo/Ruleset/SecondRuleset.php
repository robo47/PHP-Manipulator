<?php

namespace Baa\Foo\Ruleset;

use PHP\Manipulator\Ruleset;
use PHP\Manipulator\Rule;
use Baa\Foo\Rule\FifthRule;
use Baa\Foo\Rule\SixthsRule;

class FirstRuleset
extends Ruleset
{

    public function getRules()
    {
        return array(
            new SixthsRule(array('foo' => 'baa')),
            new FifthRule(array('baa' => 'foo')),
        );
    }
}