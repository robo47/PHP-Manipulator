<?php

class PHP_Manipulator_ContainerConstraint_ContainsClass
extends PHP_Manipulator_ContainerConstraint_Abstract
{

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            if ($this->evaluateConstraint('IsType', $iterator->current(), T_CLASS)) {
                return true;
            }
            $iterator->next();
        }
        return false;
    }
}
