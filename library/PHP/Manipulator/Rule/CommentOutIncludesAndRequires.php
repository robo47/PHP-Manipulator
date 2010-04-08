<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class CommentOutIncludesAndRequires
extends Rule
{
    
    public function init()
    {
        if (!$this->hasOption('globalScopeOnly')) {
            $this->setOption('globalScopeOnly', true);
        }
    }

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $tokens
     */
    public function applyRuleToTokens(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $iterator->rewind();

        $searchedTokens = array(
            T_INCLUDE,
            T_INCLUDE_ONCE,
            T_REQUIRE,
            T_REQUIRE_ONCE
        );

        $foundPairs = array();

        $searchingColon = false;
        $foundToken = null;
        $inClass = false;
        $inFunction = false;
        $bracesStatus = 0;
        $openInclude = false;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_CLASS)) {
                $inClass = true;
                $bracesStatus = 0;
            }
            if ($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
                $inFunction = true;
                $bracesStatus = 0;
            }
            if ($inClass || $inFunction) {
                if ($this->evaluateConstraint('IsOpeningCurlyBrace', $token)) {
                    $bracesStatus++;
                }
                if ($this->evaluateConstraint('IsClosingCurlyBrace', $token)) {
                    $bracesStatus--;
                    if ($bracesStatus == 0) {
                        if ($inClass) {
                            $inClass = false;
                        }
                        if ($inFunction) {
                            $inFunction = false;
                        }
                    }
                }
            }
            if ($this->_shouldCheckAndReplace($inClass, $inFunction)) {
                /* @var $token PHP\Manipulator\Token */
                if ($this->evaluateConstraint('IsType', $token, $searchedTokens) && !$openInclude) {
                    $searchingColon = true;
                    $foundToken = $token;
                    $openInclude = true;
                }

                if ($this->_isSearchingColon($searchingColon, $token) && $openInclude) {
                    $foundPairs[] = array(
                        'from' => $foundToken,
                        'to' => $token,
                    );
                    $searchingColon = false;
                    $foundToken = null;
                    $openInclude = false;
                }
            }
            $iterator->next();
        }

        foreach ($foundPairs as $params) {
            $this->manipulateContainer(
                    'CreateMultilineCommentFromTokenToToken',
                    $container,
                    $params
            );
        }
    }

    /**
     * @param boolean $searchingColon
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isSearchingColon($searchingColon, $token)
    {
        return true === $searchingColon &&
            $token->getValue() == ';';
    }

    /**
     *
     * @param boolean $inClass
     * @param boolean $inFunction
     * @return boolean
     */
    protected function _shouldCheckAndReplace($inClass, $inFunction)
    {
        $globalScopeOnly = $this->getOption('globalScopeOnly');
        if (true === $globalScopeOnly && !($inClass || $inFunction)) {
            return true;
        } elseif (false === $globalScopeOnly) {
            return true;
        }
        return false;
    }
}