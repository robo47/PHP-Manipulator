<?php

class PHP_Manipulator_Rule_ChangeLineEndings extends PHP_Manipulator_Rule_Abstract
{
    
    public function init()
    {
        if (!$this->hasOption('newline')) {
            $this->setOption('newline', "\n");
        }
    }

    /**
     *
     * @param PHP_Manipulator_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Manipulator_TokenContainer $container)
    {
        $newline = $this->getOption('newline');

        $code = $container->__toString();
        $code = preg_replace('~(\r\n|\n|\r)~', $newline, $code);
        $container->setContainer(PHP_Manipulator_TokenContainer::createTokenArrayFromCode($code));
    }
}