<?php

require_once 'PHP/Formatter/TokenContainer.php';

class PHP_Formatter_TokenTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Token::__construct
     */
    public function testDefaultConstruct()
    {
        $token = new PHP_Formatter_Token('foo');
        $this->assertEquals('foo', $token->getValue(), 'wrong value');
        $this->assertNull($token->getLinenumber(), 'wrong linenumber');
        $this->assertNull($token->getType(), 'wrong type');
    }
    
    /**
     * @covers PHP_Formatter_Token::__construct
     */
    public function testConstructorSetsValue()
    {
        $token = new PHP_Formatter_Token('baa');
        $this->assertEquals('baa', $token->getValue(), 'wrong value');
    }

    /**
     * @covers PHP_Formatter_Token::__construct
     */
    public function testConstructorSetsType()
    {
        $token = new PHP_Formatter_Token('baa', T_COMMENT);
        $this->assertEquals(T_COMMENT, $token->getType(), 'wrong type');
    }

    /**
     * @covers PHP_Formatter_Token::__construct
     */
    public function testConstructorSetsLinenumber()
    {
        $token = new PHP_Formatter_Token('baa', null, 5);
        $this->assertEquals(5, $token->getLinenumber(), 'wrong linenumber');
    }

    /**
     * @covers PHP_Formatter_Token::setValue
     * @covers PHP_Formatter_Token::getValue
     */
    public function testSetValueAndGetValue()
    {
        $token = new PHP_Formatter_Token('foo');
        $this->assertEquals('foo', $token->getValue(), 'wrong value');
        $fluent = $token->setValue('bla');
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertEquals('bla', $token->getValue(), 'wrong value');
    }

    /**
     * @covers PHP_Formatter_Token::setType
     * @covers PHP_Formatter_Token::getType
     */
    public function testSetTypeAndGetType()
    {
        $token = new PHP_Formatter_Token('foo');
        $this->assertNull($token->getType(), 'wrong type');
        $fluent = $token->setType(T_ABSTRACT);
        $this->assertSame($fluent, $token, 'No fluent interface');
        $this->assertEquals(T_ABSTRACT, $token->getType(), 'wrong type');
    }

    /**
     * @covers PHP_Formatter_Token::setLinenumber
     * @covers PHP_Formatter_Token::getLinenumber
     */
    public function testSetLinenumberAndGetLinenumber()
    {
        $token = new PHP_Formatter_Token('foo');
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
     * @covers PHP_Formatter_Token::factory
     * @dataProvider validInputFactoryProvider
     */
    public function testFactoryWithValidInput($input, $value, $type, $linenumber)
    {
        $token = PHP_Formatter_Token::factory($input);
        $this->assertType('PHP_Formatter_Token', $token, 'wrong datatype for token');

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
     * @covers PHP_Formatter_Token::factory
     * @dataProvider invalidInputFactoryProvider
     * @covers PHP_Formatter_Exception
     */
    public function testFactoryWithInvalidInput($input, $exceptionMessage)
    {
        try {
            PHP_Formatter_Token::factory($input);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
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
            PHP_Formatter_Token::factory('test'),
            null,
            true
        );
        
        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'value')),
            T_COMMENT,
            true
        );
        
        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'value')),
            T_WHITESPACE,
            false
        );
        
        $data[] = array (
            PHP_Formatter_Token::factory('test'),
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
            PHP_Formatter_Token::factory('test'),
            'test'
        );

        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 5)),
            'comment'
        );

        return $data;
    }

    /**
     * @covers PHP_Formatter_Token::__toString
     * @dataProvider __toStringProvider
     */
    public function test__toString($token, $string)
    {
        $this->assertEquals($string, (string)$token);
    }
    
    /**
     * @return array
     */
    public function equalsProvider()
    {
        $data = array();

        $data[] = array (
            PHP_Formatter_Token::factory('test'),
            PHP_Formatter_Token::factory('test'),
            false,
            true
        );

        $data[] = array (
            PHP_Formatter_Token::factory('test'),
            PHP_Formatter_Token::factory('test'),
            true,
            true
        );

        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 5)),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, 'comment', 5)),
            false,
            false
        );

        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 5)),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, 'comment', 5)),
            true,
            false
        );

        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 5)),
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 5)),
            true,
            true
        );

        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 5)),
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 4)),
            false,
            true
        );

        $data[] = array (
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 5)),
            PHP_Formatter_Token::factory(array(T_COMMENT, 'comment', 4)),
            true,
            false
        );

        return $data;
    }

    /**
     * @covers PHP_Formatter_Token::equals
     * @dataProvider equalsProvider
     */
    public function testEquals($token, $otherToken, $strict, $equals)
    {
        $this->assertSame($equals, $token->equals($otherToken, $strict), 'tokens aren\'t equal');
    }
}