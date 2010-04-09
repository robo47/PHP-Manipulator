<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class RemoveMultipleEmptyLines
extends Rule
{
    
    public function init()
    {
        if (!$this->hasOption('maxEmptyLines')) {
            $this->setOption('maxEmptyLines', 2);
        }
        if (!$this->hasOption('defaultBreak')) {
            $this->setOption('defaultBreak', "\n");
        }
    }

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function applyRuleToTokens(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $maxEmptyLines = $this->getOption('maxEmptyLines');
        $defaultBreak = $this->getOption('defaultBreak');

        $pattern = '~(((\r\n|\r|\n)[\t| ]{0,}){' . ($maxEmptyLines + 1) . ',})~';
        $replace = str_repeat($defaultBreak, $maxEmptyLines);

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP\Manipulator\Token */
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                $value = preg_replace($pattern, $replace, $token->getValue());
                $token->setValue($value);
            }
            $iterator->next();
        }
    }
}