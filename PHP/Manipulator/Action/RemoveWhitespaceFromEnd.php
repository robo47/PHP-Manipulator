<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\TokenConstraint\ContainsOnlyWhitespace
 */
class RemoveWhitespaceFromEnd
extends Action
{

    /**
     * Remove Whitespace from the end
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getReverseIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_WHITESPACE)) {
                $container->removeToken($token);
            } else if ($this->isType($token, T_INLINE_HTML)) {
                if ($this->evaluateConstraint('ContainsOnlyWhitespace', $token)) {
                    $container->removeToken($token);
                } else {
                    $token->setValue(rtrim($token->getValue()));
                    break;
                }
            } else {
                $token->setValue(rtrim($token->getValue()));
                break;
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}