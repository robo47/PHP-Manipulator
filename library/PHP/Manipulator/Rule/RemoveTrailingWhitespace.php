<?php

class PHP_Manipulator_Rule_RemoveTrailingWhitespace extends PHP_Manipulator_Rule_Abstract
{
    
    public function init()
    {
        if (!$this->hasOption('removeEmptyLinesAtFileEnd')) {
            $this->setOption('removeEmptyLinesAtFileEnd', true);
        }
        if (!$this->hasOption('defaultBreak')) {
            $this->setOption('defaultBreak', "\n");
        }
    }

    /**
     *
     * @param PHP_Manipulator_TokenContainer $container
     * @todo possible without tokens2code2tokens ?
     */
    public function applyRuleToTokens(PHP_Manipulator_TokenContainer $container)
    {
        $code = $container->toString();
        $defaultBreak = $this->getOption('defaultBreak');

        $code = preg_split('~(\r\n|\n|\r)~', $code);
        $code = array_map('rtrim', $code);
        $code = implode($defaultBreak, $code);

        if (true === $this->getOption('removeEmptyLinesAtFileEnd')) {
            $code = rtrim($code);
        }

        // @todo seems like a expensive task, with all type-checking and stuff like that ?
        $tokenArrayContainer = PHP_Manipulator_TokenContainer::createFromCode($code)
            ->getContainer();
        $container->setContainer($tokenArrayContainer);
    }
}