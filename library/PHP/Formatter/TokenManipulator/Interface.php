<?php

interface PHP_Formatter_TokenManipulator_Interface
{
    /**
     * Manipulates a Token
     * 
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null);
}