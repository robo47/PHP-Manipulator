<?php

namespace PHP\Manipulator;

use PHP\Manipulator\Exception\TokenException;

class Token
{
    const OPERATORS_WITH_TOKEN = [
        // assignment operators
        T_AND_EQUAL, // &=
        T_CONCAT_EQUAL, // .=
        T_DIV_EQUAL, // /=
        T_MINUS_EQUAL, // -=
        T_MOD_EQUAL, // &=
        T_MUL_EQUAL, // *=
        T_OR_EQUAL, // |=
        T_PLUS_EQUAL, // +=
        T_SR_EQUAL, // >>=
        T_SL_EQUAL, // <<=
        T_XOR_EQUAL, // ^=

        // logical operators
        T_LOGICAL_AND, // and
        T_LOGICAL_OR, // or
        T_LOGICAL_XOR, // xor
        T_BOOLEAN_AND, // &&
        T_BOOLEAN_OR, // ||

        // bitwise operators
        T_SL, // <<
        T_SR, // >>

        // incrementing/decrementing operators
        T_DEC, // --
        T_INC, // ++

        // comparision operators
        T_IS_EQUAL, // ==
        T_IS_GREATER_OR_EQUAL, // >=
        T_IS_IDENTICAL, // ===
        T_IS_NOT_EQUAL, // != or <>
        T_IS_NOT_IDENTICAL, // !==
        T_IS_SMALLER_OR_EQUAL, // <=

        // type-operators
        T_INSTANCEOF, // instanceof
    ];

    const OPERATORS_WITHOUT_TOKENS = [
        '=',
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @var int|null
     */
    private $lineNumber;

    /**
     * @var int|null
     */
    private $type;

    /**
     * @param string   $value
     * @param int|null $type
     * @param int|null $lineNumber
     */
    private function __construct($value, $type = null, $lineNumber = null)
    {
        $this->ensureValidValue($value);
        $this->ensureValidType($type);
        $this->ensureValidLineNumber($lineNumber);
        $this->setValue($value);
        $this->setType($type);
        $this->setLineNumber($lineNumber);
    }

    /**
     * @param string|mixed[] $input
     *
     * @return Token
     */
    public static function createFromMixed($input)
    {
        if (is_array($input)) {
            return self::createFromTokenArray($input);
        } elseif (is_string($input)) {
            return self::createFromValue($input);
        }
        $message = sprintf('Invalid datatype for creating a token: %s', gettype($input));
        throw new TokenException($message, TokenException::CREATE_ONLY_SUPPORTS_STRING_AND_ARRAY);
    }

    /**
     * @param string   $tokenValue
     * @param int|null $tokenType
     * @param int|null $tokenLineNumber
     *
     * @return Token
     */
    public static function create($tokenValue, $tokenType = null, $tokenLineNumber = null)
    {
        return new self($tokenValue, $tokenType, $tokenLineNumber);
    }

    /**
     * @param string $tokenValue
     *
     * @return Token
     */
    public static function createFromValue($tokenValue)
    {
        return new self($tokenValue);
    }

    /**
     * @param string   $tokenValue
     * @param int|null $tokenType
     *
     * @return Token
     */
    public static function createFromValueAndType($tokenValue, $tokenType)
    {
        return new self($tokenValue, $tokenType);
    }

    /**
     * @param array $token
     *
     * @return self
     */
    public static function createFromTokenArray(array $token)
    {
        if (!array_key_exists(0, $token)) {
            $message = 'Missing index 0 on token array - expected token type';
            throw new TokenException($message, TokenException::MISSING_TOKEN_TYPE);
        }
        if (!array_key_exists(1, $token)) {
            $message = 'Missing index 1 on token array - expected token value';
            throw new TokenException($message, TokenException::MISSING_TOKEN_VALUE);
        }
        if (!array_key_exists(2, $token)) {
            return new self($token[1], $token[0]);
        }

        return new self($token[1], $token[0], $token[2]);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $needle
     * @param string $replacement
     */
    public function replaceInValue($needle, $replacement)
    {
        $this->value = str_replace($needle, $replacement, $this->value);
    }

    /**
     * @param string $value
     *
     * @return Token
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * @param int $lineNumber
     *
     * @return Token
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isSingleLineComment()
    {
        if ($this->type === T_COMMENT) {
            if (strlen($this->value) >= 1 && substr($this->value, 0, 1) === '#') {
                return true;
            } elseif (strlen($this->value) >= 2 && substr($this->value, 0, 2) === '//') {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isErrorControlOperator()
    {
        if (null === $this->type && '@' === $this->value) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSingleNewline()
    {
        if ($this->value === "\n" ||
            $this->value === "\r\n" ||
            $this->value === "\r"
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isMultilineComment()
    {
        if ($this->isDocComment()) {
            return true;
        } elseif ($this->type === T_COMMENT) {
            if (strlen($this->value) > 2 && substr($this->value, 0, 2) === '/*') {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDoublequote()
    {
        if (null === $this->type && $this->value === '"') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function containsNewline()
    {
        return (bool) preg_match("~(\r|\n)~", $this->value);
    }

    /**
     * @return bool
     */
    public function containsOnlyWhitespace()
    {
        return (bool) preg_match('~^(\s)*$~', $this->value);
    }

    /**
     * @return bool
     */
    public function isWhitespace()
    {
        return $this->type === T_WHITESPACE;
    }

    /**
     * @param int $type
     *
     * @return Token
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param Token $token
     * @param bool  $strict
     *
     * @return bool
     */
    public function equals(Token $token, $strict = false)
    {
        $match = false;
        if ($this->type === $token->getType() && $this->value === $token->getValue()) {
            $match = true;
        }
        if (true === $strict && $this->lineNumber !== $token->getLineNumber()) {
            $match = false;
        }

        return $match;
    }

    /**
     * @return bool
     */
    public function isSemicolon()
    {
        if ($this->type === null && $this->value === ';') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDocComment()
    {
        return $this->type === T_DOC_COMMENT;
    }

    /**
     * @return string
     */
    public function getTokenName()
    {
        return token_name($this->getType());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @param int[]|int $type
     *
     * @return bool
     */
    public function isType($type)
    {
        if (is_array($type)) {
            foreach ($type as $tokenType) {
                if ($this->type === $tokenType) {
                    return true;
                }
            }
        } else {
            if ($this->type === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isComma()
    {
        if ($this->type === null && $this->value === ',') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isColon()
    {
        if ($this->type === null && $this->value === ':') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isQuestionMark()
    {
        if ($this->type === null && $this->value === '?') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isClosingCurlyBrace()
    {
        if ($this->type === null && $this->value === '}') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isOpeningCurlyBrace()
    {
        if ($this->type === null && $this->value === '{') {
            return true;
        }

        return false;
    }

    /**
     * @param string|string[] $value
     *
     * @return bool
     */
    public function hasValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $tokenValue) {
                if ($this->value === $tokenValue) {
                    return true;
                }
            }
        } else {
            if ($this->value === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isClosingBrace()
    {
        if ($this->type === null && $this->value === ')') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isOpeningBrace()
    {
        if ($this->type === null && $this->value === '(') {
            return true;
        }

        return false;
    }

    public function isOperator()
    {
        return $this->isType(self::OPERATORS_WITH_TOKEN) || $this->isOperatorWithoutToken();
    }

    /**
     * @return bool
     */
    public function beginsWithNewline()
    {
        return (bool) preg_match('~^(\n|\r)~', $this->value);
    }

    /**
     * @return bool
     */
    public function endsWithNewline()
    {
        return (bool) preg_match('~(\n|\r)$~', $this->value);
    }

    /**
     * @return bool
     */
    private function isOperatorWithoutToken()
    {
        foreach (self::OPERATORS_WITHOUT_TOKENS as $operator) {
            if (null === $this->type && $operator === $this->value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     *
     * @throws TokenException
     */
    private function ensureValidValue($value)
    {
        if (!is_string($value)) {
            $type    = is_object($value) ? get_class($value) : gettype($value);
            $message = sprintf('Expected token value to be int or null, got "%s"', $type);
            throw new TokenException($message, TokenException::EXPECTED_TOKEN_VALUE_TO_BE_STRING);
        }
    }

    /**
     * @param mixed $tokenType
     *
     * @throws TokenException
     */
    private function ensureValidType($tokenType)
    {
        if (!is_int($tokenType) && !is_null($tokenType)) {
            $type    = is_object($tokenType) ? get_class($tokenType) : gettype($tokenType);
            $message = sprintf('Expected token type to be int or null, got "%s"', $type);
            throw new TokenException($message, TokenException::EXPECTED_TYPE_TO_BE_INT_OR_NULL);
        }
    }

    /**
     * @param mixed $lineNumber
     *
     * @throws TokenException
     */
    private function ensureValidLineNumber($lineNumber)
    {
        if (!is_int($lineNumber) && !is_null($lineNumber)) {
            $type    = is_object($lineNumber) ? get_class($lineNumber) : gettype($lineNumber);
            $message = sprintf('Expected token line number to be int or null, got "%s"', $type);
            throw new TokenException($message, TokenException::EXPECTED_LINE_NUMBER_TO_BE_INT_OR_NULL);
        }
    }
}
