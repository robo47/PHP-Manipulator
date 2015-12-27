<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\TokenContainer;

class RemoveTrailingWhitespace extends Action
{
    const OPTION_REMOVE_EMPTY_LINES_AT_FILE_END = 'removeEmptyLinesAtFileEnd';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_REMOVE_EMPTY_LINES_AT_FILE_END)) {
            $this->setOption(self::OPTION_REMOVE_EMPTY_LINES_AT_FILE_END, true);
        }
    }

    /**
     * Remove trailing spaces
     *
     * @param TokenContainer $container
     */
    public function run(TokenContainer $container)
    {
        $newlineDetector = new NewlineDetector();
        $code            = $container->toString();
        $defaultBreak    = $newlineDetector->getNewlineFromContainer($container);

        $code = preg_split('~(\r\n|\n|\r)~', $code);
        $code = array_map('rtrim', $code);
        $code = implode($defaultBreak, $code);

        if (true === $this->getOption(self::OPTION_REMOVE_EMPTY_LINES_AT_FILE_END)) {
            $code = rtrim($code);
        }

        $container->recreateContainerFromCode($code);
    }
}
