<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_RemoveComments extends PHP_Formatter_Rule_Abstract
{
    public function init()
    {
        if (!isset($this->_options['removeDocComments'])) {
            $this->_options['removeDocComments'] = true;
        }
        if (!isset($this->_options['removeStandardComments'])) {
            $this->_options['removeStandardComments'] = true;
        }
    }

    /**
     * 
     * @param PHP_Formatter_TokenContainer $tokens
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
        $removeDocComments = $this->getOption('removeDocComments');
        $removeStandardComments = $this->getOption('removeStandardComments');
        
        $iterator = $tokens->getIterator();

        while($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            
            if (($token->isType(T_DOC_COMMENT) && $removeDocComments) ||
                ($token->isType(T_COMMENT) && $removeStandardComments)) {
                // delete comment
                unset($tokens[$iterator->key()]);
                if($this->evaluateConstraint('IsMultilineComment', $token)) {
                    // check if next Token is whitespace and begins with a NewLine
                    $iterator->next();
                    $token =  $iterator->current();
                    if(false !== $token &&
                       $token->isType(T_WHITESPACE)) {
                        // if it is a single new line remove it
                       if ($this->evaluateConstraint('IsSingleNewline', $token)) {
                           unset($tokens[$iterator->key()]);
                       }
                       // if it only begins with a new line remove that new line
                       if($this->evaluateConstraint('BeginsWithNewline', $token)) {
                           // @todo if it only contains a newline -> remove it ?
                           $this->manipulateToken('RemoveBeginNewline', $token);
                       }
                    }
                }
            }
            $iterator->next();
        }
    }
}