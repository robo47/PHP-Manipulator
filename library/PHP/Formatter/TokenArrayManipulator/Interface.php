<?php

interface PHP_Formatter_TokenContainerManipulator_Interface
{
    /**
     * Manipulates a TokenArray
     * 
     * @param PHP_Formatter_TokenContainer $tokenArray
     */
    public function manipulate(PHP_Formatter_TokenContainer $tokenArray);
}