<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ChangeLineEndings extends Action
{
    const OPTION_NEWLINE = 'newline';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_NEWLINE)) {
            $this->setOption(self::OPTION_NEWLINE, "\n");
        }
    }

    public function run(TokenContainer $container)
    {
        $newline = $this->getOption(self::OPTION_NEWLINE);

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
