<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenFinder\Result;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @uses    \PHP\Manipulator\TokenFinder\FunctionFinder
 */
class AddPublicKeyword
extends Action
{

    /**
     * @var \PHP\Manipulator\TokenContainer
     */
    protected $_container = null;

    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container)
    {
        $this->_container = $container;
        $iterator = $container->getIterator();

        $insideClassOrInterface = false;
        $classLevel = null;
        $level = 0;
        $insideMethod = false;
        $methodLevel = null;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isOpeningCurlyBrace($token)) {
                $level++;
            }
            if ($this->isClosingCurlyBrace($token)) {
                $level--;
                if ($classLevel === $level && true === $insideClassOrInterface) {
                    $insideClassOrInterface = false;
                    $classLevel = null;
                    if (true === $insideMethod) {
                        $insideMethod = false;
                        $methodLevel = null;
                    }
                }
            }
            if ($this->isType($token, array(T_CLASS, T_INTERFACE))) {
                $insideClassOrInterface = true;
                $classLevel = $level;
            }
            if (true === $insideClassOrInterface && false === $insideMethod) {
                if ($this->isType($token, T_FUNCTION)) {
                    $insideMethod = true;
                    if (!$this->isPrecededByTokenType($iterator, array(T_PUBLIC, T_PRIVATE, T_PROTECTED))) {
                        $token = $iterator->current();
                        $publicToken = new Token('public', T_PUBLIC);
                        $whitespaceToken = new Token(' ', T_WHITESPACE);

                        $this->_container->insertTokensBefore($token, array($publicToken, $whitespaceToken));
                        $iterator->update($token);
                    }
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
