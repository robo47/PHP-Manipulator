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
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $this->_container = $container;
        $iterator = $container->getIterator();

        $lastElse = null;
        $noOtherTokens = true;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_ELSE)) {
                $lastElse = $token;
                $noOtherTokens = true;
            }

            if(null !== $lastElse && !$this->_isAllowedTokenInsideEmptyElse($token)) {
                $noOtherTokens = false;
            }

            if ($this->_isEndElse($token) && true === $noOtherTokens && null !== $lastElse) {
                $start = $lastElse;
                $end = $token;
                $previous = $container->getPreviousToken($start);
                if ($this->evaluateConstraint('IsType', $end, T_ENDIF)) {
                    $end = $container->getPreviousToken($end);
                }
                $container->removeTokensFromTo($start, $end);

                $iterator = $container->getIterator();
                $iterator->seekToToken($previous);
                $lastElse = null;
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param Token $token
     * @return boolean
     */
    protected function _isEndElse(Token $token)
    {
        if($this->evaluateConstraint('IsClosingCurlyBrace', $token)) {
            return true;
        }
        if($this->evaluateConstraint('IsType', $token, T_ENDIF)) {
            return true;
        }
        return false;
    }

    /**
     * @param Token $token
     * @return boolean
     */
    protected function _isAllowedTokenInsideEmptyElse(Token $token)
    {
        if($this->evaluateConstraint('IsColon', $token) ||
           $this->evaluateConstraint('IsType', $token, array(T_ELSE, T_ENDIF, T_WHITESPACE)) ||
           $this->evaluateConstraint('IsClosingCurlyBrace', $token) ||
           $this->evaluateConstraint('IsOpeningCurlyBrace', $token)) {
            return true;
        }
        // check for ignored comments
        if (true === $this->getOption('ignoreComments') &&
            $this->evaluateConstraint('IsType', $token, array(T_COMMENT, T_DOC_COMMENT)) ) {
            return true;
        }
        return false;
    }
}