<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class ChangeLineEndings
extends Rule
{
    
    public function init()
    {
        if (!$this->hasOption('newline')) {
            $this->setOption('newline', "\n");
        }
    }

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $newline = $this->getOption('newline');

        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            $value = preg_replace(
                '~(\r\n|\n|\r)~',
                $newline,
                $token->getValue()
            );

            $token->setValue($value);

            $iterator->next();
        }
    }
}