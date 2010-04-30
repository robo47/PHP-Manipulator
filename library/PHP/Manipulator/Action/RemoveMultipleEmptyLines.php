<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class RemoveMultipleEmptyLines
extends Action
{
    public function init()
    {
        if (!$this->hasOption('maxEmptyLines')) {
            $this->setOption('maxEmptyLines', 2);
        }
        // @todo Remove this setting and use NewlineDetector
        if (!$this->hasOption('defaultBreak')) {
            $this->setOption('defaultBreak', "\n");
        }
    }

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();
        $maxEmptyLines = $this->getOption('maxEmptyLines');
        $defaultBreak = $this->getOption('defaultBreak');

        $pattern = '~(((\r\n|\r|\n)[\t| ]{0,}){' . ($maxEmptyLines + 1) . ',})~';
        $replace = str_repeat($defaultBreak, $maxEmptyLines);

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                $value = preg_replace($pattern, $replace, $token->getValue());
                $token->setValue($value);
            }
            $iterator->next();
        }
    }
}