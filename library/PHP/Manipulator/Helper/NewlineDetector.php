<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

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
        $newline = $this->_getNewlineFromToken($token);
        if (false !== $newline) {
            return $newline;
        }
        return $this->_defaultNewline;
    }

    /**
     *
     * @param Token $token
     * @param <type> $default
     * @return false|string
     */
    protected function _getNewlineFromToken(Token $token, $default = false)
    {
        $matches = array();
        if(preg_match("~(\r\n|\r|\n)~", $token->getValue(), $matches)) {
            return $matches[0];
        }
        return $default;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return string
     */
    public function getNewlineFromContainer(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        while ($iterator->valid()) {
            $newline = $this->_getNewlineFromToken($iterator->current());
            if (false !== $newline) {
                return $newline;
            }
            $iterator->next();
        }
        return $this->_defaultNewline;
    }
}