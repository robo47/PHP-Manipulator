<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class ReplaceLogicalOperatorsWithBooleanOperators
extends Action
{
    public function init()
    {
        if (!$this->hasOption('replaceAnd')) {
            $this->setOption('replaceAnd', true);
        }
        if (!$this->hasOption('replaceOr')) {
            $this->setOption('replaceOr', true);
        }
    }

    /**
     * Replace boolean and (AND)/or (OR) with logical and (&&)/or (||)
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $and = '&&';
        $or = '||';

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->_isLogicalAndAndShouldBeReplaced($token)) {
                $token->setValue($and);
                $token->setType(T_BOOLEAN_AND);
            } else if ($this->_isLogicalOrAndShouldBeReplaced($token)) {
                $token->setValue($or);
                $token->setType(T_BOOLEAN_OR);
            }
            $iterator->next();
        }
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isLogicalAndAndShouldBeReplaced(Token $token)
    {
        return ($this->isType($token, T_LOGICAL_AND) && $this->getOption('replaceAnd'));
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isLogicalOrAndShouldBeReplaced(Token $token)
    {
        return ($this->isType($token, T_LOGICAL_OR) && $this->getOption('replaceOr'));
    }
}