<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class RemoveTrailingWhitespace extends Rule
{
    
    public function init()
    {
        if (!$this->hasOption('removeEmptyLinesAtFileEnd')) {
            $this->setOption('removeEmptyLinesAtFileEnd', true);
        }
        // @todo Remove this setting and use NewlineDetector
        if (!$this->hasOption('defaultBreak')) {
            $this->setOption('defaultBreak', "\n");
        }
    }

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @todo possible without tokens2code2tokens ?
     */
    public function apply(TokenContainer $container)
    {
        $code = $container->toString();
        $defaultBreak = $this->getOption('defaultBreak');

        $code = preg_split('~(\r\n|\n|\r)~', $code);
        $code = array_map('rtrim', $code);
        $code = implode($defaultBreak, $code);

        if (true === $this->getOption('removeEmptyLinesAtFileEnd')) {
            $code = rtrim($code);
        }

        // @todo extend container to have a method which allows retokenizing from string
        $newContainer = new TokenContainer($code);
        $container->setContainer($newContainer->getContainer());
    }
}