<?php

interface PHP_Formatter_ContainerManipulator_Interface
{
    /**
     * Manipulates a Constraint on a container
     * 
     * @param PHP_Formatter_TokenContainer $container
     * @return boolean
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null);
}