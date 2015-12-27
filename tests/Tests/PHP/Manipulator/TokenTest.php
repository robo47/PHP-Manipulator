<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Exception\TokenException;
use PHP\Manipulator\Token;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Token
 */
class TokenTest extends TestCase
{
    public function testDefaultConstruct()
    {
        $token = Token::createFromValue('foo');
        $this->assertSame('foo', $token->getValue(), 'wrong value');
        $this->assertNull($token->getLineNumber(), 'wrong linenumber');
        $this->assertNull($token->getType(), 'wrong type');
    }

    public function testConstructorSetsValue()
    {
        $token = Token::createFromValue('baa');
        $this->assertSame('baa', $token->getValue(), 'wrong value');
    }

    public function testConstructorSetsType()
    {
        $token = Token::createFromValueAndType('baa', T_COMMENT);
        $this->assertSame(T_COMMENT, $token->getType(), 'wrong type');
    }

    public function testConstructorSetsLinenumber()
    {
        $token = Token::create('baa', null, 5);
        $this->assertSame(5, $token->getLineNumber(), 'wrong linenumber');
    }

    public function testSetValueAndGetValue()
    {
        $token = Token::createFromValue('foo');
        $this->assertSame('foo', $token->getValue(), 'wrong value');
        $fluent = $token->setValue('bla');
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertSame('bla', $token->getValue(), 'wrong value');
    }

    public function testSetTypeAndGetType()
    {
        $token = Token::createFromValue('foo');
        $this->assertNull($token->getType(), 'wrong type');
        $fluent = $token->setType(T_ABSTRACT);
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertSame(T_ABSTRACT, $token->getType(), 'wrong type');
    }

    public function testSetLinenumberAndGetLinenumber()
    {
        $token = Token::createFromValue('foo');
        $this->assertNull($token->getLineNumber(), 'wrong linenumber');
        $fluent = $token->setLineNumber(10);
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertSame(10, $token->getLineNumber(), 'wrong linenumber');
    }

    /**
     * @return array
     */
    public function validInputFactoryProvider()
    {
        $data = [];

        $data[] = ['foo', 'foo', null, null];
        $data[] = [[0 => T_COMMENT, 1 => '//', 2 => 5], '//', T_COMMENT, 5];
        $data[] = [[0 => T_COMMENT, 1 => '//'], '//', T_COMMENT, null];
        $data[] = [[0 => null, 1 => '//', 2 => 5], '//', null, 5];
        $data[] = [[null, '//', 5], '//', null, 5];

        return $data;
    }

    /**
     * @return array
     */
    public function invalidInputFactoryProvider()
    {
        $data = [];

        $data['Null']                      = [null, TokenException::CREATE_ONLY_SUPPORTS_STRING_AND_ARRAY];
        $data['Float: 123.5']              = [123.5, TokenException::CREATE_ONLY_SUPPORTS_STRING_AND_ARRAY];
        $data['Empty array']               = [[], TokenException::MISSING_TOKEN_TYPE];
        $data['Array with only line']      = [[2 => 5], TokenException::MISSING_TOKEN_TYPE];
        $data['Array with value and line'] = [[1 => '//', 2 => 5], TokenException::MISSING_TOKEN_TYPE];
        $data['Array with type and line']  = [[0 => T_COMMENT, 2 => 5], TokenException::MISSING_TOKEN_VALUE];

        return $data;
    }

    /**
     * @dataProvider invalidInputFactoryProvider
     *
     * @param mixed $input
     * @param int   $exceptionCode
     */
    public function testFactoryWithInvalidInput($input, $exceptionCode)
    {
        $this->setExpectedException(TokenException::class, '', $exceptionCode);
        Token::createFromMixed($input);
    }

    /**
     * @return array
     */
    public function magicToStringProvider()
    {
        $data = [];

        $data[] = [
            Token::createFromMixed('test'),
            'test',
        ];

        $data[] = [
            Token::createFromMixed([T_COMMENT, 'comment', 5]),
            'comment',
        ];

        return $data;
    }

    /**
     * @dataProvider magicToStringProvider
     *
     * @param Token  $token
     * @param string $string
     */
    public function testMagictoString(Token $token, $string)
    {
        $this->assertSame($string, (string) $token);
    }

    /**
     * @return array
     */
    public function equalsProvider()
    {
        $data = [];

        $data[] = [
            Token::createFromMixed('test'),
            Token::createFromMixed('test'),
            false,
            true,
        ];

        $data[] = [
            Token::createFromMixed('test'),
            Token::createFromMixed('test'),
            true,
            true,
        ];

        $data[] = [
            Token::createFromMixed([T_COMMENT, 'comment', 5]),
            Token::createFromMixed([T_WHITESPACE, 'comment', 5]),
            false,
            false,
        ];

        $data[] = [
            Token::createFromMixed([T_COMMENT, 'comment', 5]),
            Token::createFromMixed([T_WHITESPACE, 'comment', 5]),
            true,
            false,
        ];

        $data[] = [
            Token::createFromMixed([T_COMMENT, 'comment', 5]),
            Token::createFromMixed([T_COMMENT, 'comment', 5]),
            true,
            true,
        ];

        $data[] = [
            Token::createFromMixed([T_COMMENT, 'comment', 5]),
            Token::createFromMixed([T_COMMENT, 'comment', 4]),
            false,
            true,
        ];

        $data[] = [
            Token::createFromMixed([T_COMMENT, 'comment', 5]),
            Token::createFromMixed([T_COMMENT, 'comment', 4]),
            true,
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider equalsProvider
     *
     * @param Token $token
     * @param Token $otherToken
     * @param bool  $strict
     * @param bool  $equals
     */
    public function testEquals(Token $token, Token $otherToken, $strict, $equals)
    {
        $this->assertSame($equals, $token->equals($otherToken, $strict), 'tokens aren\'t equal');
    }

    public function testGetTokenName()
    {
        $token = Token::createFromValueAndType('  ', T_WHITESPACE);
        $this->assertSame('T_WHITESPACE', $token->getTokenName());

        $token = Token::createFromValueAndType('/* Foo */', T_COMMENT);
        $this->assertSame('T_COMMENT', $token->getTokenName());
    }

    /**
     * @return array
     */
    public function isErrorControlOperatorProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n"]),
            false,
        ];

        #1
        $data[] = [
            Token::createFromMixed([null, '@']),
            true,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, '@']),
            false,
        ];

        #3
        $data[] = [
            Token::createFromMixed([null, "\n"]),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isErrorControlOperatorProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsErrorControlOperator(Token $token, $result)
    {
        $this->assertSame($result, $token->isErrorControlOperator(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isSingleNewlineProvider()
    {
        $data = [];

        # 0
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n"]),
            true,
        ];

        # 1
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r"]),
            true,
        ];

        # 2
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r\n"]),
            true,
        ];

        # 3
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n\n"]),
            false,
        ];

        # 4
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n\r"]),
            false,
        ];

        # 5
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, " \n"]),
            false,
        ];

        # 6
        $data[] = [
            Token::createFromMixed("\n"),
            true,
        ];

        #7
        $data[] = [
            Token::createFromMixed("\r\n"),
            true,
        ];

        #8
        $data[] = [
            Token::createFromMixed("\r"),
            true,
        ];

        #9
        $data[] = [
            Token::createFromMixed("\n\r"),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isSingleNewlineProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsSingleNewline(Token $token, $result)
    {
        $this->assertSame($result, $token->isSingleNewline(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function containsNewlineProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, " \n "]),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, '  ']),
            false,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, " \r "]),
            true,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, " \r\n "]),
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider containsNewlineProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testContainsNewline(Token $token, $result)
    {
        $this->assertSame($result, $token->containsNewline(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function containsOnlyWhitespaceProvider()
    {
        $data = [];

        $data['Match. Token with only whitespace'] = [
            Token::createFromMixed([T_INLINE_HTML, "\n\t\r "]),
            true,
        ];

        $data['No match. Token containing not only whitespace'] = [
            Token::createFromMixed([T_INLINE_HTML, "a\n"]),
            false,
        ];

        $data['No match. Token containing not only whitespace #2'] = [
            Token::createFromMixed([T_INLINE_HTML, "\na\n"]),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider containsOnlyWhitespaceProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testEvaluate(Token $token, $result)
    {
        $this->assertSame($result, $token->containsOnlyWhitespace(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isTypeProvider()
    {
        $data = [];

        $data['Match. ExpectedType: T_COMMENT, Type: T_COMMENT'] = [
            Token::createFromMixed([T_COMMENT, '// some comment']),
            T_COMMENT,
            true,
        ];

        $data['No match. ExpectedType: T_WHITESPACE, Type: T_COMMENT'] = [
            Token::createFromMixed([T_COMMENT, '// some comment']),
            T_WHITESPACE,
            false,
        ];

        $data['Match. ExpectedType: [T_WHITESPACE, T_CLOSE_TAG, T_COMMENT], Type: T_COMMENT'] = [
            Token::createFromMixed([T_COMMENT, '// some comment']),
            [T_WHITESPACE, T_CLOSE_TAG, T_COMMENT],
            true,
        ];

        $data['No match. ExpectedType: T_WHITESPACE, T_CLOSE_TAG, T_DOC_COMMENT, Type: T_COMMENT'] = [
            Token::createFromMixed([T_COMMENT, '// some comment']),
            [T_WHITESPACE, T_CLOSE_TAG, T_DOC_COMMENT],
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isTypeProvider
     *
     * @param Token $token
     * @param       $type
     * @param       $result
     */
    public function testIsType(Token $token, $type, $result)
    {
        $this->assertSame($result, $token->isType($type), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isCommaProvider()
    {
        $data = [];

        $data['Comma Token, Type: null, Value: ,'] = [
            Token::createFromMixed([null, ',']),
            true,
        ];

        $data['Not Comma Token, Type: T_WHITESPACE, Value: ,'] = [
            Token::createFromMixed([T_WHITESPACE, ',']),
            false,
        ];

        $data['Not Comma Token, Type: null, Value: :'] = [
            Token::createFromMixed([null, ':']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isCommaProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsComma(Token $token, $result)
    {
        $this->assertSame($result, $token->isComma(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isColonProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([null, ':']),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, ':']),
            false,
        ];

        #2
        $data[] = [
            Token::createFromMixed([null, ';']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isColonProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsColon(Token $token, $result)
    {
        $this->assertSame($result, $token->isColon(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isClosingCurlyBraceProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([null, '{']),
            false,
        ];

        #1
        $data[] = [
            Token::createFromMixed([null, '}']),
            true,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_COMMENT, '{']),
            false,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_COMMENT, '}']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isClosingCurlyBraceProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsClosingCurlyBrace(Token $token, $result)
    {
        $this->assertSame($result, $token->isClosingCurlyBrace(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isOpeningCurlyBraceProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([null, '{']),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([null, '}']),
            false,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_COMMENT, '{']),
            false,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_COMMENT, '}']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isOpeningCurlyBraceProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsOpeningCurlyBrace(Token $token, $result)
    {
        $this->assertSame($result, $token->isOpeningCurlyBrace(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isSemicolonProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([null, ';']),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, ';']),
            false,
        ];

        #2
        $data[] = [
            Token::createFromMixed([null, ':']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isSemicolonProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsSemicolon(Token $token, $result)
    {
        $this->assertSame($result, $token->isSemicolon(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isQuestionMarkProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([null, '?']),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, '?']),
            false,
        ];

        #2
        $data[] = [
            Token::createFromMixed([null, ':']),
            false,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, ':']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isQuestionMarkProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsQuestionMark(Token $token, $result)
    {
        $this->assertSame($result, $token->isQuestionMark(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function hasValueProvider()
    {
        $data = [];

        $data['Match. ExpectedValue: foo Value: foo'] = [
            Token::createFromMixed([T_COMMENT, 'foo']),
            'foo',
            true,
        ];

        $data['No match. ExpectedValue: baa Value: foo'] = [
            Token::createFromMixed([T_COMMENT, 'foo']),
            'baa',
            false,
        ];

        $data['Match. ExpectedValue: baa, foo, blub Value: foo'] = [
            Token::createFromMixed([T_COMMENT, 'foo']),
            ['baa', 'foo', 'blub'],
            true,
        ];

        $data['No match. ExpectedValue: baa, blub, blubber Value: foo'] = [
            Token::createFromMixed([T_COMMENT, 'foo']),
            ['baa', 'blub', 'blubber'],
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider hasValueProvider
     *
     * @param Token           $token
     * @param string|string[] $value
     * @param bool            $result
     */
    public function testHasValue(Token $token, $value, $result)
    {
        $this->assertSame($result, $token->hasValue($value), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isOpeningBraceProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([null, '(']),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([null, ')']),
            false,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_COMMENT, '(']),
            false,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_COMMENT, ')']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isOpeningBraceProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsOpeningBrace(Token $token, $result)
    {
        $this->assertSame($result, $token->isOpeningBrace(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isClosingBraceProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([null, '(']),
            false,
        ];

        #1
        $data[] = [
            Token::createFromMixed([null, ')']),
            true,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_COMMENT, '(']),
            false,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_COMMENT, ')']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isClosingBraceProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsClosingBrace(Token $token, $result)
    {
        $this->assertSame($result, $token->isClosingBrace(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isSingleLineCommentProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_COMMENT, '//']),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_COMMENT, '/* */']),
            false,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_COMMENT, '#']),
            true,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, '/** */']),
            false,
        ];

        #4
        $data[] = [
            Token::createFromMixed([T_ABSTRACT, "x\n"]),
            false,
        ];

        #5
        $data[] = [
            Token::createFromMixed('//'),
            false,
        ];

        #6
        $data[] = [
            Token::createFromMixed('#'),
            false,
        ];

        #7
        $data[] = [
            Token::createFromMixed([T_COMMENT, '// Foo']),
            true,
        ];

        #7
        $data[] = [
            Token::createFromMixed([T_COMMENT, '# Foo']),
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider isSingleLineCommentProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsSingleLineComment(Token $token, $result)
    {
        $this->assertSame($result, $token->isSingleLineComment(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isOperatorProvider()
    {
        $data = [];

        $tokens = [
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

        foreach ($tokens as $type) {
            $data[] = [
                Token::createFromMixed([$type, '==']),
                true,
            ];
        }

        $data[] = [
            Token::createFromMixed([null, '=']),
            true,
        ];

        $data[] = [
            Token::createFromMixed([T_COMMENT, '=']),
            false,
        ];

        $data[] = [
            Token::createFromMixed([null, '~']),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isOperatorProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testIsOperator($token, $result)
    {
        $this->assertSame($result, $token->isOperator(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function endsWithNewlineProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n"]),
            true,
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n\r"]),
            true,
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r"]),
            true,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\n"]),
            true,
        ];

        #4
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\r\n"]),
            true,
        ];

        #5
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\r"]),
            true,
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\n "]),
            false,
        ];

        #4
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\r\n "]),
            false,
        ];

        #5
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\r "]),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider endsWithNewlineProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testEndsWithNewline(Token $token, $result)
    {
        $this->assertSame($result, $token->endsWithNewline(), 'Wrong result');
    }

    /**
     * @return array
     */
    public function beginsWithNewlineProvider()
    {
        $data = [];

        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n"]),
            true,
        ];

        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n\r"]),
            true,
        ];

        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r"]),
            true,
        ];

        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\n"]),
            false,
        ];

        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\n\r"]),
            false,
        ];

        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "x\r"]),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider beginsWithNewlineProvider
     *
     * @param Token $token
     * @param bool  $result
     */
    public function testBeginsWithNewline(Token $token, $result)
    {
        $this->assertSame($result, $token->beginsWithNewline(), 'Wrong result');
    }
}
