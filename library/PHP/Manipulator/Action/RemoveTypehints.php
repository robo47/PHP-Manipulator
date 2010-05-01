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
        
        $iterator = $container->getIterator();
        $iterator->seek($container->getOffsetByToken($startToken));
        $indentionLevel = 0;
        $inside = false;
        $arguments = array();
        $argumentTokens = array();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($indentionLevel > 0) {
                $argumentTokens[] = $token;
            }

            if ($this->evaluateConstraint('IsOpeningBrace', $token)) {
                $indentionLevel++;
            }

            if ($this->evaluateConstraint('IsClosingBrace', $token)) {
                $indentionLevel--;
            }

            if ($this->evaluateConstraint('IsComma', $token)) {
                $arguments[] = $argumentTokens;
                $argumentTokens = array();
            }

            if ($indentionLevel > 0) {
                $inside = true;
                // break if we are at the end of the arguments
            } else if ($indentionLevel === 0 && true === $inside) {
                break;
            }
            $iterator->next();
        }

        if (!empty($argumentTokens)) {
            $arguments[] = $argumentTokens;
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
        $afterAssignment = false;
        foreach ($argumentTokens as $token) {
            if ('=' === $token->getValue()) {
                $afterAssignment = true;
            }
            if ($this->evaluateConstraint('IsType', $token, T_STRING) && 'null' !== strtolower($token->getValue())) {
                $typehintToken = $token;
                break;
            }
            if ($this->evaluateConstraint('IsType', $token, T_ARRAY) && false === $afterAssignment) {
                $typehintToken = $token;
                break;
            }
        }
        return $typehintToken;
    }
}