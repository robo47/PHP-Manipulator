<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveEmptyElse
extends Action
{

    /**
     * @var PHP\Manipulator\TokenContainer
     */
    protected $_container = null;

    public function init()
    {
        if (!$this->hasOption('ignoreComments')) {
            $this->setOption('ignoreComments', false);
        }
    }

    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container)
    {
        $this->_container = $container;
        $iterator = $container->getIterator();

        $lastElse = null;
        $noOtherTokens = true;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_ELSE)) {
                $lastElse = $token;
                $noOtherTokens = true;
            }

            if (null !== $lastElse && !$this->_isAllowedTokenInsideEmptyElse($token)) {
                $noOtherTokens = false;
            }

            if ($this->_isEndElse($token) && true === $noOtherTokens && null !== $lastElse) {
                $start = $lastElse;
                $end = $token;
                $previous = $container->getPreviousToken($start);
                if ($this->isType($end, T_ENDIF)) {
                    $end = $container->getPreviousToken($end);
                }
                $container->removeTokensFromTo($start, $end);
                $iterator->reInit($previous);
                $lastElse = null;
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isEndElse(Token $token)
    {
        if ($this->isClosingCurlyBrace( $token)) {
            return true;
        }
        if ($this->isType($token, T_ENDIF)) {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isAllowedTokenInsideEmptyElse(Token $token)
    {
        if ($this->isColon($token) ||
            $this->isType($token, array(T_ELSE, T_ENDIF, T_WHITESPACE)) ||
            $this->isClosingCurlyBrace( $token) ||
            $this->isOpeningCurlyBrace( $token)) {
            return true;
        }
        // check for ignored comments
        if (true === $this->getOption('ignoreComments') &&
            $this->isType($token, array(T_COMMENT, T_DOC_COMMENT)) ) {
            return true;
        }
        return false;
    }
}