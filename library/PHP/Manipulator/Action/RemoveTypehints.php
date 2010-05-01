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
            } else if ($indentionLevel === 0 && true === $inside) {
                // break if we are at the end of the arguments
                break;
            }
            $iterator->next();
        }

        if (!empty($argumentTokens)) {
            $arguments[] = $argumentTokens;
        }

        foreach ($arguments as $argument) {
            $this->_parseSingleArgument($argument, $container);
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param array $argumentTokens
     */
    protected function _parseSingleArgument(array $argumentTokens, $container)
    {
        $afterAssignment = false;
        $typehintTokens = array();
        foreach ($argumentTokens as $token) {
            if ('=' === $token->getValue()) {
                $afterAssignment = true;
            }
            if ($this->evaluateConstraint('IsType', $token, T_STRING) && 'null' !== strtolower($token->getValue())) {
                $typehintTokens[] = $token;
            }
            if ($this->evaluateConstraint('IsType', $token, T_ARRAY) && false === $afterAssignment) {
                $typehintTokens[] = $token;
            }
            if (false === $afterAssignment && $this->evaluateConstraint('IsType', $token, T_NS_SEPARATOR)) {
                $typehintTokens[] = $token;
            }
        }
        foreach($typehintTokens as $token) {
            $container->removeToken($token);
        }
    }
}