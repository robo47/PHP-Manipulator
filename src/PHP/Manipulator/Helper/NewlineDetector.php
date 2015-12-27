<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class NewlineDetector
{
    /**
     * @var string
     */
    private $defaultNewline;

    /**
     * @param string $defaultNewline
     */
    public function __construct($defaultNewline = "\n")
    {
        $this->defaultNewline = $defaultNewline;
    }

    /**
     * @param Token $token
     *
     * @return string
     */
    public function getNewlineFromToken(Token $token)
    {
        $newline = $this->getNewline($token);
        if (false !== $newline) {
            return $newline;
        }

        return $this->defaultNewline;
    }

    /**
     * @param Token $token
     * @param mixed $default
     *
     * @return bool|string
     */
    private function getNewline(Token $token, $default = false)
    {
        if (preg_match("~(\r\n|\r|\n)~", $token->getValue(), $matches)) {
            return $matches[0];
        }

        return $default;
    }

    /**
     * @param TokenContainer $container
     *
     * @return string
     */
    public function getNewlineFromContainer(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        while ($iterator->valid()) {
            $newline = $this->getNewlineFromToken($iterator->current());
            if (false !== $newline) {
                return $newline;
            }
            $iterator->next();
        }

        return $this->defaultNewline;
    }
}
