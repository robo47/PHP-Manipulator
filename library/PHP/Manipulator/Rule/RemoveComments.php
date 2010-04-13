<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

// @todo single-line-comments containing a line-break should be replaced with a whitespace-newline
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
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $wasSingleLine = false;
            $token = $iterator->current();
            /* @var $token PHP\Manipulator\Token */
            if ($this->_isCommentAndShouldBeRemoved($token)) {
                // replace comment with whitespace
                if(0 === strpos($token->getValue(), '//')) {
                    $wasSingleLine = true;
                }
                if ($this->_normalOneLineComment($token, $container)) {
                    $token->setType(T_WHITESPACE);
                    $token->setValue("\n");
                } elseif ($this->_multiNotFollowedByWhitespaceComment($token, $container)) {
                    $container->removeToken($token);
                } elseif ($this->_multiFollowedByWhitespaceWithBreakComment($token, $container)) {
                    $container->removeToken($token);
                } else {
                    $container->removeToken($token);
                }

            }
            $iterator->next();
        }

        $container->retokenize();
    }


    protected function _normalOneLineComment(Token $token, TokenContainer $container)
    {
        if (true == preg_match('~^(\/\/|#){1,}~', $token->getValue())) {
            return true;
        }
        return false;
    }


    protected function _multiNotFollowedByWhitespaceComment(Token $token, TokenContainer $container)
    {
        if (false !== preg_match('~^(\/\*){1,}~', $token->getValue())) {
            // nächster Token ist KEIN WHITESPACE
            if (!$this->_followedByWhitespace($token, $container)) {
                return true;
            }
        }
        return false;
    }

    protected function _multiFollowedByWhitespaceWithBreakComment(Token $token, TokenContainer $container)
    {
        if (false !== preg_match('~^(\/\*){1,}~', $token->getValue())) {
            // nächster Token ist WHITESPACE
            if ($this->_followedByWhiteContainingLinebreak($token, $container)) {
                return true;
            }
        }
        return false;
    }

    protected function _followedByWhiteContainingLinebreak(Token $token, TokenContainer $container)
    {
        $nextToken = $container->getNextToken($token);
        return ($nextToken->getType() === T_WHITESPACE) && (preg_match('~(\r|\n)~', $nextToken->getValue()));
    }

    protected function _followedByWhitespace(Token $token, TokenContainer $container)
    {
        $nextToken = $container->getNextToken($token);
        return ($nextToken->getType() === T_WHITESPACE);
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