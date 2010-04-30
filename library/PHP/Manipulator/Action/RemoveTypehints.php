<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveTypehints
extends Action
{

    /**
     * Remove Typehints
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $functionTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
                $functionTokens[] = $token;
            }
            $iterator->next();
        }
        foreach ($functionTokens as $token) {
            $this->_parseFunctionArguments($container, $token);
        }
        $container->retokenize();
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $token
     */
    protected function _parseFunctionArguments(TokenContainer $container, Token $startToken)
    {
        $argumentTokens = array();
        $iterator = $container->getIterator();
        $iterator->seek($container->getOffsetByToken($startToken));
        $indentionLevel = 0;
        $inside = false;
        $arguments = array();
        $argument = array();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsOpeningBrace', $token)) {
                $indentionLevel++;
            }
            if ($indentionLevel) {
                $argumentTokens[] = $token;
            }
            if ($this->evaluateConstraint('IsClosingBrace', $token)) {
                $indentionLevel--;
            }
            // @todo IsComma-Constraint
            if ($this->evaluateConstraint('IsComma', $token)) {
                $arguments[] = $argument;
                $argument = array();
            } else if (true === $inside) {
                $argument[] = $token;
            }

            if ($indentionLevel > 0) {
                $inside = true;
            } else if ($indentionLevel === 0 && $inside) {
                break;
            }
            $iterator->next();
        }

        foreach ($arguments as $argument) {
            $typeHint = $this->_parseSingleArgument($argument);
            if ($typeHint instanceof Token) {
                $container->removeToken($typeHint);
            }
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param array $argumentTokens
     */
    protected function _parseSingleArgument(array $argumentTokens)
    {
        $typehintToken = null;
        foreach ($argumentTokens as $token) {
            if ($this->evaluateConstraint('IsType', $token, T_STRING)) {
                $typehintToken = $token;
                break;
            }
        }
        return $typehintToken;
    }
}