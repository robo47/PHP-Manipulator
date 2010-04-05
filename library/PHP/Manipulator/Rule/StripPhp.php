<?php

class PHP_Manipulator_Rule_StripPhp extends PHP_Manipulator_Rule_Abstract
{

    /**
     *
     * @param PHP_Manipulator_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Manipulator_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $open = false;
        $deleteTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Manipulator_Token */
            if ($this->evaluateConstraint('IsType', $token, array(T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO))) {
                $open = true;
            }
            if ($open) {
                $deleteTokens[] = $token;
            }
            if ($this->evaluateConstraint('IsType', $token, T_CLOSE_TAG)) {
                $open = false;
            }
            $iterator->next();
        }
        $container->removeTokens($deleteTokens);
        $container->retokenize();
    }
}