<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;

class RemoveComments
extends Action
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
     * Removes all Comments
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $helper = new NewlineDetector();
        $newline = $helper->getNewlineFromContainer($container);

        $iterator = $container->getIterator();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->_isCommentAndShouldBeRemoved($token)) {
                if ($this->_isOneLineComment($token)) {
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
    protected function _isOneLineComment(Token $token)
    {
        // @todo IsOnlinelineComment-Constraint ?
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
        return ($this->isType($token, T_DOC_COMMENT) && $this->getOption('removeDocComments'))
        || ($this->isType($token, T_COMMENT) && $this->getOption('removeStandardComments'));
    }
}