<?php

interface PHP_Manipulator_ContainerConstraint_Interface
{

    /**
     * Evaluates a constraint on a container
     *
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     * @return bool
     */
    public function evaluate(PHP_Manipulator_TokenContainer $container, $params = null);

}