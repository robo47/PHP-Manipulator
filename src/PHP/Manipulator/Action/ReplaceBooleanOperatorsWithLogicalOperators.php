<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ReplaceBooleanOperatorsWithLogicalOperators extends Action
{
    const OPTION_UPPERCASE = 'uppercase';

    const OPTION_REPLACE_AND = 'replaceAnd';

    const OPTION_REPLACE_OR = 'replaceOr';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_UPPERCASE)) {
            $this->setOption(self::OPTION_UPPERCASE, false);
        }
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

        if ($this->getOption(self::OPTION_UPPERCASE)) {
            $and = 'AND';
            $or  = 'OR';
        } else {
            $and = 'and';
            $or  = 'or';
        }

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isBooleanAndAndShouldBeReplaced($token)) {
                $token->setValue($and);
                $token->setType(T_LOGICAL_AND);
            } elseif ($this->isBooleanOrAndShouldBeReplaced($token)) {
                $token->setValue($or);
                $token->setType(T_LOGICAL_OR);
            }
            $iterator->next();
        }
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isBooleanAndAndShouldBeReplaced(Token $token)
    {
        return ($token->isType(T_BOOLEAN_AND) && $this->getOption(self::OPTION_REPLACE_AND));
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isBooleanOrAndShouldBeReplaced(Token $token)
    {
        return ($token->isType(T_BOOLEAN_OR) && $this->getOption(self::OPTION_REPLACE_OR));
    }
}
