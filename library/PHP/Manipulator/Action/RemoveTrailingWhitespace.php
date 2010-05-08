<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\Helper\NewlineDetector;

class RemoveTrailingWhitespace extends Action
{

    public function init()
    {
        if (!$this->hasOption('removeEmptyLinesAtFileEnd')) {
            $this->setOption('removeEmptyLinesAtFileEnd', true);
        }
    }

    /**
     * Remove trailing spaces
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $newlineDetector = new NewlineDetector();
        $code = $container->toString();
        $defaultBreak = $newlineDetector->getNewlineFromContainer($container);

        $code = preg_split('~(\r\n|\n|\r)~', $code);
        $code = array_map('rtrim', $code);
        $code = implode($defaultBreak, $code);

        if (true === $this->getOption('removeEmptyLinesAtFileEnd')) {
            $code = rtrim($code);
        }

        $container->reInitFromCode($code);
    }
}