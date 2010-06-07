<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\Token;
use PHP\Manipulator\Config;
use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\Action;
use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenManipulator;

// @todo ugly name
class AHelper
{

    /**
     * Load/Instantiate/Evaluate Token Constraint on a Token
     *
     * @param \PHP\Manipulator\TokenConstraint|string $constraint
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function evaluateConstraint($constraint, Token $token, $params = null, $autoPrefix = true)
    {
        $constraint = $this->getClassInstance(
            $constraint,
            'PHP\\Manipulator\\TokenConstraint\\',
            $autoPrefix
        );

        if (!$constraint instanceof TokenConstraint) {
            $message = 'constraint is not instance of PHP\\Manipulator\\TokenConstraint';
            throw new \Exception($message);
        }

        /* @var $constraint \PHP\Manipulator\TokenConstraint */
        return $constraint->evaluate($token, $params);
    }

    /**
     * Load/Instantiate/Evaluate Container Constraint on a Container
     *
     * @param \PHP\Manipulator\ContainerConstraint|string $constraint
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function evaluateContainerConstraint($constraint, TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $constraint = $this->getClassInstance(
            $constraint,
            'PHP\\Manipulator\\ContainerConstraint\\',
            $autoPrefix
        );

        if (!$constraint instanceof ContainerConstraint) {
            $message = 'constraint is not instance of PHP\\Manipulator\\ContainerConstraint';
            throw new \Exception($message);
        }

        /* @var $constraint \PHP\Manipulator\ContainerConstraint */
        return $constraint->evaluate($container, $params);
    }

    /**
     * Load/Instantiate/Run a TokenManipulator on a Token
     *
     * @param \PHP\Manipulator\TokenManipulator $manipulator
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @param boolean $autoPrefix
     */
    public function manipulateToken($manipulator, Token $token, $params = null, $autoPrefix = true)
    {
        $manipulator = $this->getClassInstance(
            $manipulator,
            'PHP\\Manipulator\\TokenManipulator\\',
            $autoPrefix
        );

        if (!$manipulator instanceof TokenManipulator) {
            $message = 'manipulator is not instance of PHP\\Manipulator\\TokenManipulator';
            throw new \Exception($message);
        }

        /* @var $manipulator \PHP\Manipulator\TokenManipulator */
        $manipulator->manipulate($token, $params);
    }

    /**
     * Load/Instantiate/Run a ContainManipulator on a Container
     *
     * @param \PHP\Manipulator\Action|string $manipulator
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     */
    public function runAction($action, TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $action = $this->getClassInstance(
            $action,
            'PHP\\Manipulator\\Action\\',
            $autoPrefix
        );

        if (!$action instanceof Action) {
            $message = 'manipulator is not instance of PHP\\Manipulator\\Action';
            throw new \Exception($message);
        }

        /* @var $manipulator  \PHP\Manipulator\Action */
        $action->run($container, $params);
    }

    /**
     * Searches a Tokencontainer starting from a Token and returns a Result-Set
     *
     * @param \PHP\Manipulator\TokenFinder|string $finder
     * @param \PHP\Manipulator\Token $token
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return \PHP\Manipulator\TokenFinder\Result
     */
    public function findTokens($finder, Token $token, TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $finder = $this->getClassInstance(
            $finder,
            'PHP\\Manipulator\\TokenFinder\\',
            $autoPrefix
        );

        if (!$finder instanceof TokenFinder) {
            $message = 'finder is not instance of PHP\\Manipulator\\TokenFinder';
            throw new \Exception($message);
        }

        /* @var $finder \PHP\Manipulator\TokenFinder */
        return $finder->find($token, $container, $params);
    }

    /**
     * Get class instance
     *
     * @param string $class
     * @param string $prefix
     * @param boolean $autoPrefix
     * @return object
     */
    public function getClassInstance($class, $prefix, $autoPrefix = true)
    {
        if (!is_string($class)) {
            return $class;
        }
        $classname = $class;
        if ($autoPrefix) {
            $classname = $prefix . $class;
        }
        return new $classname;
    }


    /**
     * @param Token $token
     * @param string|array $value
     * @return boolean
     */
    public function hasValue(Token $token, $value)
    {
        if (is_array($value)) {
            foreach ($value as $tokenValue) {
                if ($token->getValue() === $tokenValue) {
                    return true;
                }
            }
        } else {
            if ($token->getValue() === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @param array|integer $type
     * @return boolean
     */
    public function isType(Token $token, $type)
    {
        if (is_array($type)) {
            foreach ($type as $tokenType) {
                if ($token->getType() === $tokenType) {
                    return true;
                }
            }
        } else {
            if ($token->getType() === $type) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function isColon(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === ':') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     */
    public function isComma(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === ',') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function isClosingBrace(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === ')') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function isOpeningBrace(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === '(') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function isClosingCurlyBrace(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === '}') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function isOpeningCurlyBrace(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === '{') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function isSemicolon(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === ';') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function isQuestionMark(Token $token)
    {
        if ($token->getType() === null && $token->getValue() === '?') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param integer|array $type
     * @param array $allowedTypes
     * @return boolean
     */
    public function isFollowedByTokenType(Iterator $iterator, $type,
                                          array $allowedTypes = array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))
    {

        return $this->isFollowedByTokenMatchedByClosure(
            $iterator,
            ClosureFactory::getIsTypeClosure($type),
            $allowedTypes
        );
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param integer|array $type
     * @param array $allowedTypes
     * @return boolean
     */
    public function isPrecededByTokenType(Iterator $iterator, $type,
                                          array $allowedTypes = array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))
    {
        return $this->isPrecededByTokenMatchedByClosure(
            $iterator,
            ClosureFactory::getIsTypeClosure($type),
            $allowedTypes
        );
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param string $value
     * @param array $allowedTypes
     * @return boolean
     */
    public function isFollowedByTokenValue(Iterator $iterator, $value,
                                           array $allowedTypes = array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))
    {
        return $this->isFollowedByTokenMatchedByClosure(
            $iterator,
            ClosureFactory::getHasValueClosure($value),
            $allowedTypes
        );
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param string $value
     * @param array $allowedTypes
     * @return boolean
     */
    public function isPrecededByTokenValue(Iterator $iterator, $value,
                                           array $allowedTypes = array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))
    {
        return $this->isPrecededByTokenMatchedByClosure(
            $iterator,
            ClosureFactory::getHasValueClosure($value),
            $allowedTypes
        );
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param \Closure $closure
     * @param array $allowedTypes
     * @return boolean
     * @todo ugly name
     */
    public function isFollowedByTokenMatchedByClosure(Iterator $iterator, \Closure $closure,
                                                      array $allowedTypes = array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))
    {
        return $this->isFollowed(
            $iterator,
            $closure,
            ClosureFactory::getIsTypeClosure($allowedTypes)
        );
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param \Closure $closure
     * @param array $allowedTypes
     * @return boolean
     * @todo ugly name
     */
    public function isPrecededByTokenMatchedByClosure(Iterator $iterator, \Closure $closure,
                                                      array $allowedTypes = array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))
    {
        return $this->isPreceded(
            $iterator,
            $closure,
            ClosureFactory::getIsTypeClosure($allowedTypes)
        );
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param \Closure $isSearchedToken
     * @param \Closure $isAllowedToken
     * @param \PHP\Manipulator\Token $found
     * @return boolean
     */
    public function isPreceded(Iterator $iterator, \Closure $isSearchedToken, \Closure $isAllowedToken, Token &$found = null)
    {
        $token = $iterator->current();
        $result = false;
        $iterator->previous();

        while($iterator->valid()) {
            $currentToken = $iterator->current();
            if ($isSearchedToken($currentToken)) {
                $found = $currentToken;
                $result = true;
                break;
            }
            if (!$isAllowedToken($currentToken)) {
                break;
            }
            $iterator->previous();
        }

        $iterator->seekToToken($token);
        return $result;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param \Closure $isSearchedToken
     * @param \Closure $isAllowedToken
     * @param \PHP\Manipulator\Token $found
     * @return boolean
     */
    public function isFollowed(Iterator $iterator, \Closure $isSearchedToken, \Closure $isAllowedToken, Token &$found = null)
    {
        $token = $iterator->current();
        $result = false;
        $iterator->next();

        while($iterator->valid()) {
            $currentToken = $iterator->current();
            if ($isSearchedToken($currentToken)) {
                $found = $currentToken;
                $result = true;
                break;
            }
            if (!$isAllowedToken($currentToken)) {
                break;
            }
            $iterator->next();
        }

        $iterator->seekToToken($token);
        return $result;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @return \PHP\Manipulator\Token
     */
    public function getMatchingBrace(Iterator $iterator)
    {
        $token = $iterator->current();
        $brace = $token->getValue();

        $searchForward = true;
        $incrementBrace = '';
        $decrementBrace = '';
        switch($brace) {
            case '[':
                $incrementBrace = '[';
                $decrementBrace = ']';
                break;
            case ']':
                $incrementBrace = ']';
                $decrementBrace = '[';
                $searchForward = false;
                break;
            case '(':
                $incrementBrace = '(';
                $decrementBrace = ')';
                break;
            case ')':
                $searchForward = false;
                $incrementBrace = ')';
                $decrementBrace = '(';
                break;
            case '{':
                $incrementBrace = '{';
                $decrementBrace = '}';
                break;
            case '}':
                $incrementBrace = '}';
                $decrementBrace = '{';
                $searchForward = false;
                break;
            default:
                $message = 'Token is no brace like (,),{,},[ or ]';
                throw new \Exception($message);
        }

        $level = 1;
        $this->_nextToken($iterator, $searchForward);
        $foundToken = null;
        while($iterator->valid()) {
            $currentToken = $iterator->current();
            if (null === $currentToken->getType()) {
                if ($currentToken->getValue() === $incrementBrace) {
                    $level++;
                } else if ($currentToken->getValue() === $decrementBrace) {
                    $level--;
                }
            }
            if ($level === 0) {
                $foundToken = $currentToken;
                break;
            }
            $this->_nextToken($iterator, $searchForward);
        }
        $iterator->update($token);
        return $foundToken;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param boolean $forward
     */
    protected function _nextToken(Iterator $iterator, $forward = true)
    {
        if ($forward) {
            $iterator->next();
        } else {
            $iterator->previous();
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param \Closure $closure
     * @return \PHP\Manipulator\Token
     */
    public function getNextMatchingToken(Iterator $iterator, \Closure $closure)
    {
        $token = $iterator->current();

        $foundToken = null;
        while($iterator->valid()) {
            $currentToken = $iterator->current();

            if ($closure($currentToken)) {
                $foundToken = $currentToken;
                break;
            }
            $iterator->next();
        }
        $iterator->update($token);
        return $foundToken;
    }
}