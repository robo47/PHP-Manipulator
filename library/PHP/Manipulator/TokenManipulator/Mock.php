<?php

class PHP_Manipulator_TokenManipulator_Mock
extends PHP_Manipulator_TokenManipulator_Abstract
{

    /**
     * @var boolean
     */
    public static $called = false;

    /**
     * @param PHP_Manipulator_Token $token
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_Token $token, $params = null)
    {
        self::$called = true;
    }
}
