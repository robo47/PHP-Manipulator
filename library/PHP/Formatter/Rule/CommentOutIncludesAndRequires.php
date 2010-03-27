<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_CommentOutIncludesAndRequires extends PHP_Formatter_Rule_Abstract
{

    public function init()
    {
        if (!$this->hasOption('globalScopeOnly')) {
            $this->setOption('globalScopeOnly', true);
        }
    }
    
    /**
     *
     * @param PHP_Formatter_TokenContainer $tokens
     * @todo Wont work with nested include/require yet: include implode('', include 'test1.php');
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
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

        while($iterator->valid()) {
            $token = $iterator->current();
            if($this->evaluateConstraint('IsType', $token, T_CLASS)) {
                $inClass = true;
                $bracesStatus = 0;
            }
            if($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
                $inFunction = true;
                $bracesStatus = 0;
            }
            if ($inClass || $inFunction) {
                if ($this->evaluateConstraint('IsOpeningCurlyBrace', $token)) {
                    $bracesStatus++;
                }
                if ($this->evaluateConstraint('IsClosingCurlyBrace', $token)) {
                    $bracesStatus--;
                    if($bracesStatus == 0) {
                        if ($inClass) {
                            $inClass = false;
                        }
                        if ($inFunction) {
                            $inFunction = false;
                        }
                    }
                }
            }
            if($this->_shouldCheckAndReplace($inClass , $inFunction)) {
                /* @var $token PHP_Formatter_Token */
                if($this->evaluateConstraint('IsType', $token, $searchedTokens)) {
                    $searchingColon = true;
                    $foundToken = $token;
                }

                if ($this->_isSearchingColon($searchingColon, $token)) {
                    $foundPairs[] = array(
                        'from' => $foundToken,
                        'to' => $token,
                    );
                    $searchingColon = false;
                    $foundToken = null;
                }
            }
            $iterator->next();
        }

        foreach($foundPairs as $params) {
            $this->manipulateContainer(
                'CreateMultilineCommentFromTokenToToken',
                $container,
                $params
            );
        }
    }

    /**
     * @param boolean $searchingColon
     * @param PHP_Formatter_Token $token
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
        if(true === $globalScopeOnly && !($inClass || $inFunction)) {
            return true;
        } elseif (false === $globalScopeOnly) {
            return true;
        }
        return false;
    }
}