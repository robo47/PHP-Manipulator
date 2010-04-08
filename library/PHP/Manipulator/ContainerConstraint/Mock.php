<?php

namespace PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\TokenContainer;

class Mock
extends ContainerConstraint
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(TokenContainer $container, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
