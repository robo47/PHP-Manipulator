<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class RemoveTypehints
extends Action
{

    /**
     * Remove Typehints
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $functionTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_FUNCTION)) {
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
        $iterator->seekToToken($startToken);
        $indentionLevel = 0;
        $inside = false;
        $arguments = array();
        $argumentTokens = array();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($this->isOpeningBrace( $token)) {
                $indentionLevel++;
            } else if ($this->isClosingBrace( $token)) {
                $indentionLevel--;
            }

            // next argument
            if ($this->isComma($token)) {
                $arguments[] = $argumentTokens;
                $argumentTokens = array();
            }

            if ($indentionLevel > 0) {
                $argumentTokens[] = $token;
                $inside = true;
            } else if ($indentionLevel === 0 && true === $inside) {
                // break if we are at the end of the arguments
                break;
            }
            $iterator->next();
        }

        // add last argument
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
        foreach ($argumentTokens as $token) {
            if ('=' === $token->getValue()) {
                break;
            }
            if ($this->isType($token, array(T_STRING, T_ARRAY, T_NS_SEPARATOR))) {
                $container->removeToken($token);
            }
        }
    }
}