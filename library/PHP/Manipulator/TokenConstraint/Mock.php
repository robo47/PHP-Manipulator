<?php

class PHP_Manipulator_TokenConstraint_Mock
extends PHP_Manipulator_TokenConstraint_Abstract
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param PHP_Manipulator_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_Token $token, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
