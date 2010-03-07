<?php

interface PHP_Formatter_TokenManipulator_Interface
{
    /**
     * Manipulates a Token
     *
     * Returns true if it has done something, false if not
     * 
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null);
}