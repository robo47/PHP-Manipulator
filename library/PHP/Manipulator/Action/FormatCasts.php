<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class FormatCasts
extends Action
{
    public function init()
    {
        if (!$this->hasOption('searchedTokens')) {
            $this->setOption(
                'searchedTokens',
                array(
                    T_INT_CAST => '(int)',
                    T_BOOL_CAST => '(bool)',
                    T_DOUBLE_CAST => '(double)',
                    T_OBJECT_CAST => '(object)',
                    T_STRING_CAST => '(string)',
                    T_UNSET_CAST => '(unset)',
                    T_ARRAY_CAST => '(array)',
                )
            );
        }
    }

    /**
     * Format casts
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param array $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $searchedTokens = $this->getOption('searchedTokens');

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, array_keys($searchedTokens))) {
                $token->setValue($searchedTokens[$token->getType()]);
            }
            $iterator->next();
        }
    }
}