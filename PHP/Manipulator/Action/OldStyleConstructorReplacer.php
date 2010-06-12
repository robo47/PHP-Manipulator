<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class OldStyleConstructorReplacer
extends Action
{

    /**
     * @var integer
     */
    protected $_level = 0;

    /**
     * @var \SplStack
     */
    protected $_classStack = null;

    /**
     * @var integer
     */
    protected $_maxLevel = 0;

    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $this->_classStack = new \SplStack();

        $classname = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            $this->_checkLevel($token);
            if ($this->isType($token, T_CLASS)) {
                $this->_classStack->push($this->_level);
                $iterator->next();
                while ($iterator->valid()) {
                    $token = $iterator->current();
                    $this->_checkLevel($token);
                    if ($this->isType($token, T_STRING)) {
                        $classname = $token->getValue();
                        break;
                    }
                    $iterator->next();
                }
            }
            if (!$this->_classStack->isEmpty()) {
                if ($this->isType($token, T_FUNCTION)) {
                    while ($iterator->valid()) {
                        $token = $iterator->current();
                        $this->_checkLevel($token);
                        if ($this->isType($token, T_STRING)) {
                            if (strtolower($token->getValue()) === strtolower($classname)) {
                                $token->setValue('__construct');
                            }
                            break;
                        }
                        if ($this->_classStack->isEmpty()) {
                            break;
                        }
                        $iterator->next();
                    }
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param \PHP\Manipulator\Token $token
     */
    protected function _checkLevel(Token $token)
    {
        if ($this->isOpeningCurlyBrace( $token)) {
            $this->_level++;
            $this->_maxLevel = max(array($this->_level, $this->_maxLevel));
        }
        if ($this->isClosingCurlyBrace( $token)) {
            $this->_level--;
            if (!$this->_classStack->isEmpty() &&
                $this->_level === $this->_classStack[count($this->_classStack) -1]) {
                $this->_classStack->pop();
            }
        }

    }
}