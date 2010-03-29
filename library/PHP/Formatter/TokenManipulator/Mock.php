<?php

require_once 'PHP/Formatter/TokenManipulator/Abstract.php';

class PHP_Formatter_TokenManipulator_Mock
extends PHP_Formatter_TokenManipulator_Abstract
{

    /**
     * @var boolean
     */
    public static $called = false;

    /**
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null)
    {
        self::$called = true;
    }
}
