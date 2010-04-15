<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;

class RemoveComments
extends Rule
{
    
    public function init()
    {
        if (!$this->hasOption('removeDocComments')) {
            $this->setOption('removeDocComments', true);
        }
        if (!$this->hasOption('removeStandardComments')) {
            $this->setOption('removeStandardComments', true);
        }
    }

    /**
     * Removes Comments
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $helper = new NewlineDetector();
        $newline = $helper->getNewlineFromContainer($container);
        // @todo optimize, find out whitespace only once directlyfrom the container
        $iterator = $container->getIterator();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->_isCommentAndShouldBeRemoved($token)) {
                if ($this->_isOneLineComment($token, $container)) {
                    $token->setType(T_WHITESPACE);
                    $token->setValue($newline);
                } else {
                    $container->removeToken($token);
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param Token $token
     * @param TokenContainer $container
     * @return boolean
     */
    protected function _isOneLineComment(Token $token, TokenContainer $container)
    {
        if (true == preg_match('~^(\/\/|#){1,}~', $token->getValue())) {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isCommentAndShouldBeRemoved(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_DOC_COMMENT) && $this->getOption('removeDocComments'))
            || ($this->evaluateConstraint('IsType', $token, T_COMMENT) && $this->getOption('removeStandardComments'));
    }
}