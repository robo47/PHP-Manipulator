<?php

class PHP_Formatter_ContainerManipulator_Mock
extends PHP_Formatter_ContainerManipulator_Abstract
{

    /**
     * @var boolean
     */
    public static $called = true;

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        self::$called = true;
    }
}
