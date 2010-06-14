<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\TokenConstraint\EndsWithNewline
 * @uses    \PHP\Manipulator\TokenConstraint\ContainsOnlyWhitespace
 * @uses    \PHP\Manipulator\Action\RemoveWhitespaceFromEnd
 */
class StripUnneededPhpCloseTag
extends Action
{
    public function init()
    {
        if (!$this->hasOption('stripWhitespaceFromEnd')) {
            $this->setOption('stripWhitespaceFromEnd', false);
        }
    }

    /**
     * Remove unneded ?> from the file-end
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $stripWhitespaceFromEnd = $this->getOption('stripWhitespaceFromEnd');

        $iterator = $container->getReverseIterator();
        $helper = new NewlineDetector("\n");

        while ($iterator->valid()) {
            $token = $iterator->current();

            if (!$this->_isNotAllowedTag($token)) {
                break;
            } else if ($this->isType($token, T_CLOSE_TAG)) {
                if ($this->evaluateConstraint('EndsWithNewline', $token)) {

                    $newline = $helper->getNewlineFromToken($token);
                    $token->setType(T_WHITESPACE);
                    $token->setValue($newline);
                } else {
                    $container->removeToken($token);
                }
                break;
            }
            $iterator->next();
        }
        $container->retokenize();
        if (true === $stripWhitespaceFromEnd) {
            $this->runAction('RemoveWhitespaceFromEnd', $container);
        }
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isNotAllowedTag(Token $token)
    {
        return $this->isType($token, array(T_WHITESPACE, T_CLOSE_TAG)) ||
               $this->evaluateConstraint('ContainsOnlyWhitespace', $token);
    }
}