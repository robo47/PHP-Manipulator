<?php

require_once 'PHP/Formatter/ContainerConstraint/Abstract.php';

class PHP_Formatter_ContainerConstraint_Mock
extends PHP_Formatter_ContainerConstraint_Abstract
{

    /**
     * @var boolean
     */
    public static $return = true;
    
    public function evaluate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
