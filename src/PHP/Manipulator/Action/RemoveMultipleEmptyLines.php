<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveMultipleEmptyLines extends Action
{
    const OPTION_MAX_EMPTY_LINES = 'maxEmptyLines';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_MAX_EMPTY_LINES)) {
            $this->setOption(self::OPTION_MAX_EMPTY_LINES, 2);
        }
    }

    public function run(TokenContainer $container)
    {
        $newlineDetector = new NewlineDetector();
        $iterator        = $container->getIterator();
        $defaultBreak    = $newlineDetector->getNewlineFromContainer($container);

        /** @var Token $previous */
        $previous = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isWhitespace()) {
                $maxEmptyLines = $this->getOption(self::OPTION_MAX_EMPTY_LINES);
                if (null !== $previous && $previous->isSingleLineComment()) {
                    $maxEmptyLines = $this->getOption(self::OPTION_MAX_EMPTY_LINES) - 1;
                }
                $pattern = sprintf('~(((\r\n|\r|\n)([\t| ]*)){%u,}([\t| ]*))~', $maxEmptyLines+1);
                $replace = str_repeat($defaultBreak, $maxEmptyLines).'\4';
                $value   = preg_replace($pattern, $replace, $token->getValue());
                $token->setValue($value);
            }
            $previous = $token;
            $iterator->next();
        }
    }
}
