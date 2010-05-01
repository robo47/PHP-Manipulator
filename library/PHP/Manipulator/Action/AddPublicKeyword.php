<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenFinder\Result;

class AddPublicKeyword
extends Action
{

    /**
     * @todo Many Actions need access to container in methods, maybe that could be moved to Action
     *
     * @var \PHP\Manipulator\TokenContainer
     */
    protected $_container = null;

    /**
     * Run Action
     *
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $this->_container = $container;
        $iterator = $container->getIterator();

        $insideClass = false;
        $classLevel = null;
        $level = 0;
        $insideMethod = false;
        $methodLevel = null;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isOpeningCurlyBrace( $token, T_CLASS)) {
                $level++;
            }
            if ($this->isClosingCurlyBrace( $token, T_CLASS)) {
                $level--;

                if ($classLevel === $level  && true === $insideClass) {
                    $insideClass = false;
                    $classLevel = null;
                    if (true === $insideMethod) {
                        $insideMethod = false;
                        $methodLevel = null;
                    }
                }
            }
            if ($this->isType($token, T_CLASS)) {
                $insideClass = true;
                $classLevel = $level;
            }
            if (true === $insideClass && false === $insideMethod) {
                if ($this->isType($token, T_FUNCTION)) {
                    $insideMethod = true;
                    $result = $this->findTokens('FunctionFinder', $token, $container, array('includeMethodProperties' => true));
                    $this->_checkAndAddPublic($result);
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    protected function _checkAndAddPublic(Result $result)
    {
        if(!$this->_checkResultContainsTokenType($result, array(T_PUBLIC, T_PRIVATE, T_PROTECTED))) {
            $previous = $this->_container->getPreviousToken($result->getFirstToken());
            $publicToken = new Token('public', T_PUBLIC);
            $whitespaceToken = new Token(' ', T_WHITESPACE);

            $this->_container->insertTokenAfter($previous, $publicToken);
            $this->_container->insertTokenAfter($publicToken, $whitespaceToken);
        }
    }

    /**
     *
     * @param Result $result
     * @param array $tokentype
     * @return boolean
     */
    protected function _checkResultContainsTokenType(Result $result, array $tokentypes)
    {
        foreach($result->getTokens() as $token) {
            if($this->isType($token, $tokentypes)) {
                return true;
            }
        }
        return false;
    }
}