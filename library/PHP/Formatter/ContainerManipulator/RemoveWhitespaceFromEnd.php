<?php

class PHP_Formatter_ContainerManipulator_RemoveWhitespaceFromEnd
extends PHP_Formatter_ContainerManipulator_Abstract
{

    /**
     * Manipulate
     *
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        $iterator = $container->getReverseIterator();

        $tokensToDelete = array();

        while($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                $tokensToDelete[] = $token;
            } else {
                break;
            }
            $iterator->next();
        }

        foreach($tokensToDelete as $token) {
            $container->removeToken($token);
        }
    }
}