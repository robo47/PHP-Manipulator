<?php

require_once 'PHP/Formatter/ContainerManipulator/Abstract.php';

class PHP_Formatter_ContainerManipulator_Mock
extends PHP_Formatter_ContainerManipulator_Abstract
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
