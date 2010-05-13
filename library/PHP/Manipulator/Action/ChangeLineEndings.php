<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ChangeLineEndings
extends Action
{
    public function init()
    {
        if (!$this->hasOption('newline')) {
            $this->setOption('newline', "\n");
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
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