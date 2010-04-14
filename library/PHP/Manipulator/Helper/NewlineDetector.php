<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\Token;

class NewlineDetector
{
    /**
     * @var string
     */
    protected $_defaultNewline = "\n";

    /**
     *
     * @param string $defaultNewline
     */
    public function __construct($defaultNewline = "\n")
    {
        $this->setDefaultNewline($defaultNewline);
    }

    /**
     * @return string
     */
    public function getDefaultNewline()
    {
        return $this->_defaultNewline;
    }

    /**
     * @param string $defaultNewline
     * @return \PHP\Manipulator\Helper\NewlineDetector *Provides Fluent Interface*
     */
    public function setDefaultNewline($defaultNewline)
    {
        $this->_defaultNewline = $defaultNewline;
        return $this;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return string
     */
    public function getNewline(Token $token)
    {
        $newline = $this->_defaultNewline;
        $matches = array();
        if(preg_match("~(\r\n|\r|\n)~", $token->getValue(), $matches)) {
            return $matches[0];
        }
        return $newline;
    }
}