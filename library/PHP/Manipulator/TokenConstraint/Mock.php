<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class Mock
extends TokenConstraint
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
