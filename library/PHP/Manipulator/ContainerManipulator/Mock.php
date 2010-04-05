<?php

class PHP_Manipulator_ContainerManipulator_Mock
extends PHP_Manipulator_ContainerManipulator_Abstract
{

    /**
     * @var boolean
     */
    public static $called = true;

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_TokenContainer $container, $params = null)
    {
        self::$called = true;
    }
}
