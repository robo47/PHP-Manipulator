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
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();
        $maxEmptyLines = $this->getOption('maxEmptyLines');
        $defaultBreak = $this->getOption('defaultBreak');

        $previous = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_WHITESPACE)) {
                if (null !== $previous && $this->evaluateConstraint('IsSinglelineComment', $previous)) {
                    $maxEmptyLines = $this->getOption('maxEmptyLines') - 1;
                } else {
                    $maxEmptyLines = $this->getOption('maxEmptyLines');
                }
                $pattern = '~(((\r\n|\r|\n)([\t| ]{0,})){' . ($maxEmptyLines + 1) . ',}([\t| ]{0,}))~';
                $replace = str_repeat($defaultBreak, $maxEmptyLines) . '\4';
                $value = preg_replace($pattern, $replace, $token->getValue());
                $token->setValue($value);
            }
            $previous = $token;
            $iterator->next();
        }
    }
}