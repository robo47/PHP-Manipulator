<?php

interface PHP_Formatter_ContainerConstraint_Interface
{

    /**
     * Evaluates a constraint on a container
     *
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     * @return bool
     */
    public function evaluate(PHP_Formatter_TokenContainer $container, $params = null);

}