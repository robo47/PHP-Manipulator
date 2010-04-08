<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;
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
    public function applyRuleToTokens(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP\Manipulator\Token */

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
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isCommentAndShouldBeRemoved(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_DOC_COMMENT) && $this->getOption('removeDocComments'))
            || ($this->evaluateConstraint('IsType', $token, T_COMMENT) && $this->getOption('removeStandardComments'));
    }
}