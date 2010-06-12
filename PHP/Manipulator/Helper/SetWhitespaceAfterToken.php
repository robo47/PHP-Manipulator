<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class SetWhitespaceAfterToken
extends AHelper
{

    /**
     * @var PHP\Manipulator\TokenContainer
     */
    protected $_container = null;

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        if (!is_array($params)) {
            $message = 'invalid input $params should be an array';
            throw new \Exception($message);
        }
        if (!isset($params['tokens'])) {
            $message = "key 'tokens' not found in \$params";
            throw new \Exception($message);
        }
        if (!isset($params['whitespace'])) {
            $message = "key 'whitespace' not found in \$params";
            throw new \Exception($message);
        }

        $tokens = $params['tokens'];
        $whitespace = $params['whitespace'];
        $this->_container = $container;
        $iterator = $container->getIterator();

        while($iterator->valid()) {
            if (in_array($iterator->current(), $tokens)) {
                $this->setWhitespace($iterator, $whitespace);
            }
            $iterator->next();
        }
    }

    /**
     * @param Iterator $iterator
     * @param array $whitespace
     */
    public function setWhitespace(Iterator $iterator, array $whitespace)
    {
        $token = $iterator->current();
        $this->_moveIteratorToTargetToken($iterator);
        $targetToken = $iterator->current();

        $tokenValue = $this->getWhitespaceForToken($token, $whitespace);

        $containerChanger = false;
        if (null !== $targetToken) {
            if ($this->isType($targetToken, T_WHITESPACE)) {
                if (empty($tokenValue)) {
                    $this->_container->removeToken($targetToken);
                    $containerChanger = true;
                } else {
                    $targetToken->setValue($tokenValue);
                }
            } else {
                if (!empty($tokenValue)) {
                    $newToken = Token::factory(array(T_WHITESPACE, $tokenValue));
                    $this->_insertToken($newToken, $iterator);
                    $containerChanger = true;
                }
            }
        }
        $this->_moveIteratorBackFromTagetToken($iterator);
        if (true === $containerChanger) {
            $iterator->update($iterator->current());
        }
    }

    /**
     * @param Iterator $iterator
     */
    protected function _moveIteratorToTargetToken(Iterator $iterator)
    {
        $iterator->next();
    }


    /**
     * @param Iterator $iterator
     */
    protected function _moveIteratorBackFromTagetToken(Iterator $iterator)
    {
        $iterator->previous();
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $targetToken
     * @param \PHP\Manipulator\Token $newToken
     */
    protected function _insertToken(Token $newToken, Iterator $iterator)
    {
        $this->_container->insertTokenBefore($iterator->current(), $newToken);
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @param array $whitespaces
     * @return mixed
     */
    public function getWhitespaceForToken(Token $token, array $whitespaces)
    {
        if (null === $token->getType()) {
            $tokenval = $token->getValue();
        } else {
            $tokenval = $token->getType();
        }
        if (array_key_exists($tokenval, $whitespaces)) {
            return $whitespaces[$tokenval];
        } else {
            $message = 'No option found for: ' . $token->getTokenName() .
            ' (' . $tokenval . ')';
            throw new \Exception($message);
        }
    }
}