<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_CommentOutIncludesAndRequires extends PHP_Formatter_Rule_Abstract
{

    public function init()
    {
        if (!isset($this->_options['onlyPreClass'])) {
            $this->_options['onlyPreClass'] = true;
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
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
        if ($this->getOption('onlyPreClass')) {
            throw new Exception('not supported yet');
        }

        $iterator = $tokens->getIterator();
        $iterator->rewind();

        do {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            if ($this->isTokenOfType($token, T_INCLUDE)
                || $this->isTokenOfType($token, T_INCLUDE_ONCE)
                || $this->isTokenOfType($token, T_REQUIRE)
                || $this->isTokenOfType($token, T_REQUIRE_ONCE)) {

                // @todo use multi-line-comment to not mess with potential code behind the require/include
                //       or add another option and add a break afterwards
                $commentToken = PHP_Formatter_Token::factory(
                        array(
                        0 => T_COMMENT,
                        1 => '//'
                        )
                );

                $tokens->insertBeforeKey($iterator->key(), $commentToken);
            }


        } while ($iterator->next() || $iterator->valid());
    }
}