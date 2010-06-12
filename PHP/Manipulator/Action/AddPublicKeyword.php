<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenFinder\Result;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
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
                    $result = $this->findTokens(
                        'FunctionFinder',
                        $token,
                        $container,
                        array('includeMethodProperties' => true)
                    );
                    $this->_checkAndAddPublic($result);
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param \PHP\Manipulator\TokenFinder\Result $result
     */
    protected function _checkAndAddPublic(Result $result)
    {
        $tokens = array(T_PUBLIC, T_PRIVATE, T_PROTECTED);
        if (!$this->_resultContainsTokenType($result, $tokens)) {
            $previous = $this->_container->getPreviousToken($result->getFirstToken());
            $publicToken = new Token('public', T_PUBLIC);
            $whitespaceToken = new Token(' ', T_WHITESPACE);

            $this->_container->insertTokenAfter($previous, $publicToken);
            $this->_container->insertTokenAfter($publicToken, $whitespaceToken);
        }
    }

    /**
     * @param \PHP\Manipulator\TokenFinder\Result $result
     * @param array $tokentype
     * @return boolean
     */
    protected function _resultContainsTokenType(Result $result, array $tokentypes)
    {
        foreach ($result->getTokens() as $token) {
            if ($this->isType($token, $tokentypes)) {
                return true;
            }
        }
        return false;
    }
}