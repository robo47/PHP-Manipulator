<?php

class PHP_Manipulator_ContainerConstraint_Mock
extends PHP_Manipulator_ContainerConstraint_Abstract
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_TokenContainer $container, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
