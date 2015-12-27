<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use PHP\Manipulator\Exception\ResultException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenFinder\Result;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenFinder\Result
 */
class ResultTest extends TestCase
{
    public function testDefaultConstruct()
    {
        $result = new Result();
        $this->assertTrue($result->isEmpty());
        $this->assertCount(0, $result);
    }

    public function testAddToken()
    {
        $token  = Token::createFromValue('foo');
        $token2 = Token::createFromValue('baa');
        $result = new Result();

        $this->assertCount(0, $result->getTokens());

        $result->addToken($token);
        $this->assertCount(1, $result->getTokens());
        $this->assertContains($token, $result->getTokens());

        $result->addToken($token2);
        $this->assertCount(2, $result->getTokens());
        $this->assertContains($token2, $result->getTokens());
    }

    public function testGetTokens()
    {
        $token  = Token::createFromValue('foo');
        $token2 = Token::createFromValue('baa');
        $result = new Result();
        $result->addToken($token);
        $result->addToken($token2);

        $tokens = $result->getTokens();

        $this->assertInternalType('array', $tokens);
        $this->assertCount(2, $tokens);
        $this->assertContains($token, $tokens);
        $this->assertContains($token2, $tokens);
    }

    public function testGetFirstToken()
    {
        $token  = Token::createFromValue('foo');
        $token2 = Token::createFromValue('baa');
        $result = new Result();
        $result->addToken($token);
        $result->addToken($token2);

        $this->assertSame($token, $result->getFirstToken());
    }

    public function testAddTokensProvidesFluentInterface()
    {
        $result = new Result();
        $fluent = $result->addToken(Token::createFromValue('foo'));
        $this->assertSame($result, $fluent);
    }

    public function testGetLastToken()
    {
        $token  = Token::createFromValue('foo');
        $token2 = Token::createFromValue('baa');
        $result = new Result();
        $result->addToken($token);
        $result->addToken($token2);

        $this->assertSame($token2, $result->getLastToken());
    }

    public function testGetLastTokenWithOnlyOneTokenInResult()
    {
        $token  = Token::createFromValue('foo');
        $result = new Result();
        $result->addToken($token);

        $this->assertSame($token, $result->getLastToken());
    }

    public function testGetLastTokenThrowsExceptionOnEmptyResult()
    {
        $result = new Result();
        $this->setExpectedException(
            ResultException::class,
            '',
            ResultException::EMPTY_RESULT
        );
        $result->getLastToken();
    }

    public function testGetFirstTokenThrowsExceptionOnEmptyResult()
    {
        $result = new Result();
        $this->setExpectedException(
            ResultException::class,
            '',
            ResultException::EMPTY_RESULT
        );
        $result->getFirstToken();
    }

    public function testIsEmpty()
    {
        $result = new Result();
        $this->assertTrue($result->isEmpty());
        $result->addToken(Token::createFromValue('foo'));
        $this->assertFalse($result->isEmpty());
    }

    public function testCount()
    {
        $result = new Result();
        $this->assertCount(0, $result);
        $result->addToken(Token::createFromValue('Foo'));
        $this->assertCount(1, $result);
        $result->addToken(Token::createFromValue('Foo'));
        $this->assertCount(2, $result);
    }

    public function testClean()
    {
        $t1     = Token::createFromValue('foo');
        $t2     = Token::createFromValue('baa');
        $t3     = Token::createFromValue('blub');
        $result = Result::factory([$t1, $t2, $t3]);
        $this->assertCount(3, $result);
        $result->clean();
        $this->assertCount(0, $result);
    }

    public function testFactoryWithEmptyArray()
    {
        $result = Result::factory([]);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function testFactoryWithNonEmptyArray()
    {
        $t1     = Token::createFromValue('foo');
        $t2     = Token::createFromValue('baa');
        $t3     = Token::createFromValue('blub');
        $result = Result::factory([$t1, $t2, $t3]);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->isEmpty());
        $this->assertCount(3, $result);

        $this->assertSame($t1, $result->getFirstToken());
        $this->assertSame($t3, $result->getLastToken());

        $this->assertSame([$t1, $t2, $t3], $result->getTokens());
    }
}
