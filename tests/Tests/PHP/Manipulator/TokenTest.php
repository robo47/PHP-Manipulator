<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Token;

/**
 * @group Token
 */
class TokenTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Token::__construct
     */
    public function testDefaultConstruct()
    {
        $token = new Token('foo');
        $this->assertEquals('foo', $token->getValue(), 'wrong value');
        $this->assertNull($token->getLinenumber(), 'wrong linenumber');
        $this->assertNull($token->getType(), 'wrong type');
    }

    /**
     * @covers \PHP\Manipulator\Token::__construct
     */
    public function testConstructorSetsValue()
    {
        $token = new Token('baa');
        $this->assertEquals('baa', $token->getValue(), 'wrong value');
    }

    /**
     * @covers \PHP\Manipulator\Token::__construct
     */
    public function testConstructorSetsType()
    {
        $token = new Token('baa', T_COMMENT);
        $this->assertEquals(T_COMMENT, $token->getType(), 'wrong type');
    }

    /**
     * @covers \PHP\Manipulator\Token::__construct
     */
    public function testConstructorSetsLinenumber()
    {
        $token = new Token('baa', null, 5);
        $this->assertEquals(5, $token->getLinenumber(), 'wrong linenumber');
    }

    /**
     * @covers \PHP\Manipulator\Token::setValue
     * @covers \PHP\Manipulator\Token::getValue
     */
    public function testSetValueAndGetValue()
    {
        $token = new Token('foo');
        $this->assertEquals('foo', $token->getValue(), 'wrong value');
        $fluent = $token->setValue('bla');
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertEquals('bla', $token->getValue(), 'wrong value');
    }

    /**
     * @covers \PHP\Manipulator\Token::setType
     * @covers \PHP\Manipulator\Token::getType
     */
    public function testSetTypeAndGetType()
    {
        $token = new Token('foo');
        $this->assertNull($token->getType(), 'wrong type');
        $fluent = $token->setType(T_ABSTRACT);
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertEquals(T_ABSTRACT, $token->getType(), 'wrong type');
    }

    /**
     * @covers \PHP\Manipulator\Token::setLinenumber
     * @covers \PHP\Manipulator\Token::getLinenumber
     */
    public function testSetLinenumberAndGetLinenumber()
    {
        $token = new Token('foo');
        $this->assertNull($token->getLinenumber(), 'wrong linenumber');
        $fluent = $token->setLinenumber(10);
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertEquals(10, $token->getLinenumber(), 'wrong linenumber');
    }

    /**
     * @return array
     */
    public function validInputFactoryProvider()
    {
        $data = array();

        $data[] = array('foo', 'foo', null, null);
        $data[] = array(array(0 => T_COMMENT, 1 => '//', 2 => 5), '//', T_COMMENT, 5);
        $data[] = array(array(0 => T_COMMENT, 1 => '//'), '//', T_COMMENT, null);
        $data[] = array(array(0 => null, 1 => '//', 2 => 5), '//', null, 5);
        $data[] = array(array(null, '//', 5), '//', null, 5);

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Token::factory
     * @dataProvider validInputFactoryProvider
     */
    public function testFactoryWithValidInput($input, $value, $type, $linenumber)
    {
        $token = Token::factory($input);
        $this->assertType('PHP\Manipulator\Token', $token, 'wrong datatype for token');

        $this->assertEquals($value, $token->getValue(), 'wrong value');
        $this->assertEquals($type, $token->getType(), 'wrong type');
        $this->assertEquals($linenumber, $token->getLinenumber(), 'wrong linenumber');
    }

    /**
     * @return array
     */
    public function invalidInputFactoryProvider()
    {
        $data = array();

        $data[] = array(null, 'invalid datatype for creating a token: NULL');
        $data[] = array(123.5, 'invalid datatype for creating a token: double');
        $data[] = array(array(), 'Array for creating token misses key 0 and/or 1');
        $data[] = array(array(2 => 5), 'Array for creating token misses key 0 and/or 1');
        $data[] = array(array(1 => '//', 2 => 5), 'Array for creating token misses key 0 and/or 1');
        $data[] = array(array(0 => T_COMMENT, 2 => 5), 'Array for creating token misses key 0 and/or 1');

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Token::factory
     * @dataProvider invalidInputFactoryProvider
     * @covers \Exception
     */
    public function testFactoryWithInvalidInput($input, $exceptionMessage)
    {
        try {
            Token::factory($input);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals($exceptionMessage, $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @return array
     */
    public function isTypeProvider()
    {
        $data = array();

        $data[] = array (
            Token::factory('test'),
            null,
            true
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'value')),
            T_COMMENT,
            true
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'value')),
            T_WHITESPACE,
            false
        );

        $data[] = array (
            Token::factory('test'),
            T_WHITESPACE,
            false
        );

        return $data;
    }

    /**
     * @return array
     */
    public function __toStringProvider()
    {
        $data = array();

        $data[] = array (
            Token::factory('test'),
            'test'
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'comment', 5)),
            'comment'
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Token::__toString
     * @dataProvider __toStringProvider
     */
    public function test__toString($token, $string)
    {
        $this->assertEquals($string, (string) $token);
    }

    /**
     * @return array
     */
    public function equalsProvider()
    {
        $data = array();

        $data[] = array (
            Token::factory('test'),
            Token::factory('test'),
            false,
            true
        );

        $data[] = array (
            Token::factory('test'),
            Token::factory('test'),
            true,
            true
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'comment', 5)),
            Token::factory(array(T_WHITESPACE, 'comment', 5)),
            false,
            false
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'comment', 5)),
            Token::factory(array(T_WHITESPACE, 'comment', 5)),
            true,
            false
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'comment', 5)),
            Token::factory(array(T_COMMENT, 'comment', 5)),
            true,
            true
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'comment', 5)),
            Token::factory(array(T_COMMENT, 'comment', 4)),
            false,
            true
        );

        $data[] = array (
            Token::factory(array(T_COMMENT, 'comment', 5)),
            Token::factory(array(T_COMMENT, 'comment', 4)),
            true,
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Token::equals
     * @dataProvider equalsProvider
     */
    public function testEquals($token, $otherToken, $strict, $equals)
    {
        $this->assertSame($equals, $token->equals($otherToken, $strict), 'tokens aren\'t equal');
    }
}