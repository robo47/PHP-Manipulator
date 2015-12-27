<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use SplStack;

class OldStyleConstructorReplacer extends Action
{
    /**
     * @var int
     */
    private $level = 0;

    /**
     * @var SplStack
     */
    private $classStack;

    /**
     * @var int
     */
    private $maxLevel = 0;

    public function run(TokenContainer $container)
    {
        $iterator         = $container->getIterator();
        $this->classStack = new SplStack();

        $classname = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            $this->checkLevel($token);
            if ($token->isType(T_CLASS)) {
                $this->classStack->push($this->level);
                $iterator->next();
                while ($iterator->valid()) {
                    $token = $iterator->current();
                    $this->checkLevel($token);
                    if ($token->isType(T_STRING)) {
                        $classname = $token->getValue();
                        break;
                    }
                    $iterator->next();
                }
            }
            if (!$this->classStack->isEmpty()) {
                if ($token->isType(T_FUNCTION)) {
                    while ($iterator->valid()) {
                        $token = $iterator->current();
                        $this->checkLevel($token);
                        if ($token->isType(T_STRING)) {
                            if (strtolower($token->getValue()) === strtolower($classname)) {
                                $token->setValue('__construct');
                            }
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
     * @param Token $token
     */
    private function checkLevel(Token $token)
    {
        if ($token->isOpeningCurlyBrace()) {
            $this->level++;
            $this->maxLevel = max([$this->level, $this->maxLevel]);
        }
        if ($token->isClosingCurlyBrace()) {
            $this->level--;
            if (!$this->classStack->isEmpty() &&
                $this->level === $this->classStack[count($this->classStack) - 1]
            ) {
                $this->classStack->pop();
            }
        }
    }
}
