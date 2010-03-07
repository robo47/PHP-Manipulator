<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_CommentOutIncludesAndRequires extends PHP_Formatter_Rule_Abstract
{

    public function init()
    {
        if (!$this->hasOptions('onlyOutsideClass')) {
            $this->setOption('onlyOutsideClass', true);
        }
    }

    /**
     *
     * @param string $value
     * @param integer $type
     * @param integer $line
     * @return array|string
     */
    public function tokenFactory($value, $type = null, $line = null)
    {
        if (null !== $type) {
            $token = array (
                0 => $type,
                1 => $value
            );
            if (null !== $line) {
                $token[2] = $line;
            }
        } else {
            $token = $value;
        }
        return $token;
    }

    /**
     *
     * @param PHP_Formatter_TokenContainer $tokens
     * @todo checking if something is in the same line after the require which get's commented out too ? what about multi-line-comment ARROUND the require ?
     * @todo Wont work with nested include/require yet: include implode('', include 'test1.php');
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        if ($this->getOption('onlyPreClass')) {
            throw new Exception('not supported yet');
        }

        $iterator = $tokens->getIterator();
        $iterator->rewind();

        $found = 0;

        $searchedTokens = array(
            T_INCLUDE,
            T_INCLUDE_ONCE,
            T_REQUIRE,
            T_REQUIRE_ONCE
        );

        $foundPairs = array();
        
        $searchingColon = false;
        $foundToken = null;

        while($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            if (in_array($token->getType(), $searchedTokens)) {
                $searchingColon = true;
                $foundToken = $token;
            }

            if (true === $searchingColon &&
                $token->getValue() == ';') {
                $foundPairs[] = array(
                    'from' => $foundToken,
                    'to' => $token,
                );
                $searchingColon = false;
                $foundToken = null;
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
}