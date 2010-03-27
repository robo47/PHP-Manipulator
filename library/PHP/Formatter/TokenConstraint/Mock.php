<?php

require_once 'PHP/Formatter/TokenConstraint/Abstract.php';

class PHP_Formatter_TokenConstraint_Mock
extends PHP_Formatter_TokenConstraint_Abstract
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param PHP_Formatter_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Formatter_Token $token, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
