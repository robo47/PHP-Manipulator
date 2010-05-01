<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

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

    protected $_maxLevel = 0;

    /**
     * Run Action
     *
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();
        $this->_classStack = new \SplStack();

        $classname = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            $this->_checkLevel($token);
            if ($this->evaluateConstraint('IsType', $token, T_CLASS)) {
                $this->_classStack->push($this->_level);
                $iterator->next();
                while ($iterator->valid()) {
                    $token = $iterator->current();
                    $this->_checkLevel($token);
                    if ($this->evaluateConstraint('IsType', $token, T_STRING)) {
                        $classname = $token->getValue();
                        break;
                    }
                    $iterator->next();
                }
            }
            if (!$this->_classStack->isEmpty()) {
                if ($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
                    while ($iterator->valid()) {
                        $token = $iterator->current();
                        $this->_checkLevel($token);
                        if ($this->evaluateConstraint('IsType', $token, T_STRING)) {
                            if (strtolower($token->getValue()) === strtolower($classname)) {
                                $token->setValue('__construct');
                            }
                            break;
                        }
                        $this->_checkStack();
                        $iterator->next();
                    }
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param Token $token
     */
    protected function _checkLevel(Token $token)
    {
        if ($this->evaluateConstraint('IsOpeningCurlyBrace', $token)) {
            $this->_level++;
            $this->_maxLevel = max(array($this->_level, $this->_maxLevel));
        }
        if ($this->evaluateConstraint('IsClosingCurlyBrace', $token)) {
            $this->_level--;
            if (!$this->_classStack->isEmpty() &&
                 $this->_level === $this->_classStack[count($this->_classStack) -1]) {
                $this->_classStack->pop();
            }
        }

    }

    protected function _checkStack()
    {
        if ($this->_classStack->isEmpty()) {
            break;
        }
    }
}