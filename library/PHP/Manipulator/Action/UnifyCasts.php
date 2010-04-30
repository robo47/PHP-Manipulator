<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class UnifyCasts
extends Action
{

    /**
     * Manipulate
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param array $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $searchedTokens = array(
            T_INT_CAST => '(int)',
            T_BOOL_CAST => '(bool)',
            T_DOUBLE_CAST => '(double)',
            T_OBJECT_CAST => '(object)',
            T_STRING_CAST => '(string)',
            T_UNSET_CAST => '(unset)',
            T_ARRAY_CAST => '(array)',
        );

        // array_merge() won't work with integer-keys!
        foreach ($params as $cast => $value) {
            $searchedTokens[$cast] = $value;
        }

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, array_keys($searchedTokens))) {
                $newValue = $searchedTokens[$token->getType()];
                if ($token->getValue() != $newValue) {
                    $token->setValue($newValue);
                }
            }
            $iterator->next();
        }
    }
}