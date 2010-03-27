<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_RemoveComments extends PHP_Formatter_Rule_Abstract
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
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */

            if ($this->_isCommentAndShouldBeRemoved($token)) {
                // delete comment
                unset($container[$iterator->key()]);
                if ($this->evaluateConstraint('IsMultilineComment', $token)) {
                    // check if next Token is whitespace and begins with a NewLine
                    $iterator->next();
                    $token = $iterator->current();
                    if (false !== $token &&
                        $this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                        // if it is a single new line remove it
                        if ($this->evaluateConstraint('IsSingleNewline', $token)) {
                            unset($container[$iterator->key()]);
                        }
                        // if it only begins with a new line remove that new line
                        if ($this->evaluateConstraint('BeginsWithNewline', $token)) {
                            // @todo if it only contains a newline -> remove it ?
                            $this->manipulateToken('RemoveBeginNewline', $token);
                        }
                    }
                }
            }
            $iterator->next();
        }
    }

    /**
     *
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isCommentAndShouldBeRemoved($token)
    {
        // $token->isType(T_COMMENT) && $removeStandardComments
        return ($this->evaluateConstraint('IsType', $token, T_DOC_COMMENT) && $this->getOption('removeDocComments'))
            ||
            ($this->evaluateConstraint('IsType', $token, T_COMMENT) && $this->getOption('removeStandardComments'));
    }
}