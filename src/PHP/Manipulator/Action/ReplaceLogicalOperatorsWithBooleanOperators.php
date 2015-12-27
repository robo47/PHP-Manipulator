<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ReplaceLogicalOperatorsWithBooleanOperators extends Action
{
    const OPTION_REPLACE_AND = 'replaceAnd';

    const OPTION_REPLACE_OR = 'replaceOr';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_REPLACE_AND)) {
            $this->setOption(self::OPTION_REPLACE_AND, true);
        }
        if (!$this->hasOption(self::OPTION_REPLACE_OR)) {
            $this->setOption(self::OPTION_REPLACE_OR, true);
        }
    }

    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $and = '&&';
        $or  = '||';

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isLogicalAndAndShouldBeReplaced($token)) {
                $token->setValue($and);
                $token->setType(T_BOOLEAN_AND);
            } elseif ($this->isLogicalOrAndShouldBeReplaced($token)) {
                $token->setValue($or);
                $token->setType(T_BOOLEAN_OR);
            }
            $iterator->next();
        }
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isLogicalAndAndShouldBeReplaced(Token $token)
    {
        return ($token->isType(T_LOGICAL_AND) && $this->getOption(self::OPTION_REPLACE_AND));
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isLogicalOrAndShouldBeReplaced(Token $token)
    {
        return ($token->isType(T_LOGICAL_OR) && $this->getOption(self::OPTION_REPLACE_OR));
    }
}
