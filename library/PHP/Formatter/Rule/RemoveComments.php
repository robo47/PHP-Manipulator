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
        
        $delete = array();

        $iterator = $tokens->getIterator();

        while($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
             $pos = $iterator->key();
            if($this->checkTokenConstraint('IsMultilineComment', $token)) {
                if (($token->isType(T_DOC_COMMENT) && $removeDocComments) ||
                    ($token->isType(T_COMMENT) && $removeStandardComments)) {
                    // check if next Token is whitespace and is a NewLine
                    $iterator->next();
                    $token =  $iterator->current();
                    if(false !== $token &&
                       $token->isType(T_WHITESPACE)) {
                       if($this->checkTokenConstraint('BeginsWithNewline', $token)) {
                           // @todo Manipulator which removes first Newline
                       }
                    }
                }
            }
            $iterator->next();
        }
    }
}