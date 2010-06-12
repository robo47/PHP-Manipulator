<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class SwitchFinder
extends TokenFinder
{

    /**
     * Finds tokens
     *
     * @param \PHP\Manipulator\Token $token
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return \PHP\Manipulator\TokenFinder\Result
     */
    public function find(Token $token, TokenContainer $container, $params = null)
    {
        if (!$this->isType($token, T_SWITCH)) {
            throw new Exception('Starttoken is not T_SWITCH');
        }
        $result = new Result();
        $iterator = $container->getIterator();
        $iterator->seekToToken($token);

        $level = 0;
        $inside = false;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isOpeningCurlyBrace( $token)) {
                if (0 === $level) {
                    $inside = true;
                }
                $level++;
            }
            if ($this->isClosingCurlyBrace( $token)) {
                $level--;
            }
            $result->addToken($token);

            if ($inside && 0 === $level) {
                break;
            }
            $iterator->next();
        }
        return $result;
    }
}