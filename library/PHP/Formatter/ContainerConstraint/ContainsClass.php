<?php

class PHP_Formatter_ContainerConstraint_ContainsClass
extends PHP_Formatter_ContainerConstraint_Abstract
{

    /**
     * @var boolean
     */
    public static $return = true;

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            if($this->evaluateConstraint('IsType', $iterator->current(), T_CLASS)) {
                return true;
            }
            $iterator->next();
        }
        return false;
    }
}
