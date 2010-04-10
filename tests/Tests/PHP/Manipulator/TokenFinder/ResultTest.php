<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder_Result
 */
class ResultTest
extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result
     */
    public function testDefaultConstruct()
    {
        $result = new Result();
        $this->assertTrue($result->isEmpty());
        $this->assertCount(0, $result);
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::addToken
     */
    public function testAddToken()
    {
        $token = new Token('foo');
        $token2 = new Token('baa');
        $result = new Result();

        $this->assertCount(0, $result->getTokens());

        $result->addToken($token);
        $this->assertCount(1, $result->getTokens());
        $this->assertContains($token, $result->getTokens());

        $result->addToken($token2);
        $this->assertCount(2, $result->getTokens());
        $this->assertContains($token2, $result->getTokens());
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::getTokens
     */
    public function testGetTokens()
    {
        $token = new Token('foo');
        $token2 = new Token('baa');
        $result = new Result();
        $result->addToken($token);
        $result->addToken($token2);

        $tokens = $result->getTokens();

        $this->assertType('array', $tokens);
        $this->assertCount(2, $tokens);
        $this->assertContains($token, $tokens);
        $this->assertContains($token2, $tokens);
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::getFirstToken
     */
    public function testGetFirstToken()
    {
        $token = new Token('foo');
        $token2 = new Token('baa');
        $result = new Result();
        $result->addToken($token);
        $result->addToken($token2);

        $this->assertSame($token, $result->getFirstToken());
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::getLastToken
     */
    public function testAddTokensProvidesFluentInterface()
    {
        $result = new Result();
        $fluent = $result->addToken(new Token('foo'));
        $this->assertSame($result, $fluent);
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::getLastToken
     */
    public function testGetLastToken()
    {
        $token = new Token('foo');
        $token2 = new Token('baa');
        $result = new Result();
        $result->addToken($token);
        $result->addToken($token2);

        $this->assertSame($token2, $result->getLastToken());
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::getLastToken
     */
    public function testGetLastTokenThrowsExceptionOnEmptyResult()
    {
        $result = new Result();
        try {
            $result->getLastToken();
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Result is Empty", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::getFirstToken
     */
    public function testGetFirstTokenThrowsExceptionOnEmptyResult()
    {
        $result = new Result();
        try {
            $result->getFirstToken();
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Result is Empty", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::isEmpty
     */
    public function testIsEmpty()
    {
        $result = new Result();
        $this->assertTrue($result->isEmpty());
        $result->addToken(new Token('foo'));
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::count
     */
    public function testCount()
    {
        $result = new Result();
        $this->assertCount(0, $result);
        $result->addToken(new Token('Foo'));
        $this->assertCount(1, $result);
        $result->addToken(new Token('Foo'));
        $this->assertCount(2, $result);
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::factory
     */
    public function testFactoryWithEmptyArray()
    {
        $result = Result::factory(array());
        $this->assertType('\PHP\Manipulator\TokenFinder\Result', $result);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\Result::factory
     */
    public function testFactoryWithNonEmptyArray()
    {
        $t1 = new Token('foo');
        $t2 = new Token('baa');
        $t3 = new Token('blub');
        $result = Result::factory(array($t1, $t2, $t3));
        $this->assertType('\PHP\Manipulator\TokenFinder\Result', $result);
        $this->assertFalse($result->isEmpty());
        $this->assertCount(3, $result);

        $this->assertSame($t1, $result->getFirstToken());
        $this->assertSame($t3, $result->getLastToken());

        $this->assertSame(array($t1, $t2, $t3), $result->getTokens());
    }
}