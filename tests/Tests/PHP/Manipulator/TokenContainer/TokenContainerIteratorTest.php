<?php

namespace Tests\PHP\Manipulator\TokenContainer;

use PHP\Manipulator\Exception\TokenContainerException;
use PHP\Manipulator\Exception\TokenContainerIteratorException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;
use ReflectionClass;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenContainer\TokenContainerIterator
 */
class TokenContainerIteratorTest extends TestCase
{
    public function testIteratorClass()
    {
        $reflection = new ReflectionClass(TokenContainerIterator::class);
        $this->assertTrue($reflection->isIterateable());
        $this->assertTrue($reflection->implementsInterface('Countable'));
        $this->assertTrue($reflection->hasMethod('previous'));
    }

    public function testGetContainer()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new TokenContainerIterator($container);
        $this->assertSame($container, $iterator->getContainer());
    }

    /**
     * @return TokenContainer
     */
    public function getTestContainerWithHoles()
    {
        $tokens = [
            0 => Token::createFromMixed([null, "<?php\n"]),
            1 => Token::createFromMixed([null, 'dummy']),
            2 => Token::createFromMixed([null, 'echo']),
            3 => Token::createFromMixed([null, 'dummy']),
            4 => Token::createFromMixed([null, ' ']),
            5 => Token::createFromMixed([null, '\$var']),
            6 => Token::createFromMixed([null, ';']),
        ];
        $container = TokenContainer::factory($tokens);
        unset($container[1]);
        unset($container[3]);

        return $container;
    }

    public function testIterator()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new TokenContainerIterator($container);

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertSame(0, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertSame(2, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertSame(4, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertSame(5, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertSame(6, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertFalse($iterator->valid());

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertSame(6, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertSame(5, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertSame(4, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertSame(2, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertSame(0, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertFalse($iterator->valid());

        $iterator->rewind();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertSame(0, $iterator->key(), 'Wrong key');
    }

    public function testSeekToToken()
    {
        $tokens = [
            0 => Token::createFromMixed([null, "<?php\n"]),
            1 => Token::createFromMixed([null, 'dummy']),
            2 => Token::createFromMixed([null, 'echo']),
            3 => Token::createFromMixed([null, 'dummy']),
            4 => Token::createFromMixed([null, ' ']),
            5 => Token::createFromMixed([null, '\$var']),
            6 => Token::createFromMixed([null, ';']),
        ];
        $container = TokenContainer::factory($tokens);
        $iterator  = new TokenContainerIterator($container);

        foreach ($tokens as $token) {
            $iterator->seekToToken($token);
            $this->assertTrue($iterator->valid());
            $this->assertSame($token, $iterator->current());
        }
    }

    public function testSeekToTokenThrowsOutOfBoundsExceptionIfTokenNotInContainer()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new TokenContainerIterator($container);
        $this->setExpectedException(
            TokenContainerException::class,
            'Foo',
            TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER
        );
        $iterator->seekToToken(Token::createFromValue('Foo'));
    }

    public function testCountable()
    {
        $container = TokenContainer::createEmptyContainer();
        $iterator  = new TokenContainerIterator($container);
        $this->assertCount(0, $iterator);

        $container = $this->getTestContainerWithHoles();
        $iterator  = new TokenContainerIterator($container);
        $this->assertCount(5, $iterator);
    }

    public function testUpdate()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new TokenContainerIterator($container);

        $this->assertCount(count($container), $iterator);

        $container[] = Token::createFromValue('Foo');

        $this->assertCount(count($container) - 1, $iterator);
        $iterator->update();
        $this->assertCount(count($container), $iterator);
    }

    public function testUpdateWithoutSeek()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new TokenContainerIterator($container);
        $iterator->seekToToken($container[5]);
        $this->assertSame($iterator->current(), $container[5]);
        $iterator->update();
        $this->assertSame($iterator->current(), $container[0]);
    }

    public function testUpdateWithSeek()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new TokenContainerIterator($container);
        $iterator->seekToToken($container[5]);
        $this->assertSame($iterator->current(), $container[5]);
        $iterator->update($container[5]);
        $this->assertSame($iterator->current(), $container[5]);
    }

    public function testFluentInterfaces()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = $container->getIterator();
        $fluent    = $iterator->seekToToken($container[2]);
        $this->assertSame($iterator, $fluent);
        $fluent = $iterator->update($container[2]);
        $this->assertSame($iterator, $fluent);
    }

    public function testGetNext()
    {
        $t1        = Token::createFromMixed('Token1');
        $t2        = Token::createFromMixed('Token2');
        $container = TokenContainer::factory([$t1, $t2]);

        $iterator = $container->getIterator();

        $next = $iterator->getNext();

        $this->assertSame($t2, $next);
        $this->assertSame($t1, $iterator->current());
    }

    public function testGetPrevious()
    {
        $t1        = Token::createFromMixed('Token1');
        $t2        = Token::createFromMixed('Token2');
        $container = TokenContainer::factory([$t1, $t2]);

        $iterator = $container->getIterator();
        $iterator->seekToToken($t2);
        $previous = $iterator->getPrevious();

        $this->assertSame($t1, $previous);
        $this->assertSame($t2, $iterator->current());
    }

    public function testGetNextThrowsExceptionIfNoNextToken()
    {
        $t1        = Token::createFromMixed('Token1');
        $container = TokenContainer::factory([$t1]);

        $iterator = $container->getIterator();

        $this->setExpectedException(
            TokenContainerIteratorException::class,
            '',
            TokenContainerIteratorException::NO_NEXT_TOKEN
        );
        $iterator->getNext();
    }

    public function testGetPreviousThrowsExceptionIfNoPreviousToken()
    {
        $t1        = Token::createFromMixed('Token1');
        $container = TokenContainer::factory([$t1]);

        $iterator = $container->getIterator();

        $this->setExpectedException(
            TokenContainerIteratorException::class,
            '',
            TokenContainerIteratorException::NO_PREVIOUS_TOKEN
        );
        $iterator->getPrevious();
    }
}
