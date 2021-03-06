<?php

namespace Tests\PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenContainer
 * @group TokenContainer\Iterator
 */
class IteratorTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator
     */
    public function testIteratorClass()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\TokenContainer\Iterator');
        $this->assertTrue($reflection->isIterateable());
        $this->assertTrue($reflection->implementsInterface('SeekableIterator'));
        $this->assertTrue($reflection->implementsInterface('Countable'));
        $this->assertTrue($reflection->hasMethod('previous'));
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::getContainer
     */
    public function testGetContainer()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);
        $this->assertSame($container, $iterator->getContainer());
    }

    /**
     * @return \PHP\Manipulator\TokenContainer
     */
    public function getTestContainerWithHoles()
    {
        $tokens = array(
            0 => Token::factory(array(null, "<?php\n")),
            1 => Token::factory(array(null, "dummy")),
            2 => Token::factory(array(null, 'echo')),
            3 => Token::factory(array(null, "dummy")),
            4 => Token::factory(array(null, ' ')),
            5 => Token::factory(array(null, '\$var')),
            6 => Token::factory(array(null, ';')),
        );
        $container = new TokenContainer($tokens);
        unset($container[1]);
        unset($container[3]);

        return $container;
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator
     */
    public function testIterator()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertEquals(0, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertEquals(2, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertEquals(4, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertEquals(5, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertEquals(6, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertFalse($iterator->valid());

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertEquals(6, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertEquals(5, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertEquals(4, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertEquals(2, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertEquals(0, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertFalse($iterator->valid());

        $iterator->rewind();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertEquals(0, $iterator->key(), 'Wrong key');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::seek
     * @covers \PHP\Manipulator\TokenContainer\Iterator::<protected>
     */
    public function testSeek()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);

        $iterator->next();
        $iterator->next();

        $iterator->seek(0);
        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());

        $iterator->seek(2);
        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());

        $iterator->seek(4);
        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());

        $iterator->seek(5);
        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());

        $iterator->seek(6);
        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::seekToToken
     * @covers \PHP\Manipulator\TokenContainer\Iterator::<protected>
     */
    public function testSeekToToken()
    {
        $tokens = array(
            0 => Token::factory(array(null, "<?php\n")),
            1 => Token::factory(array(null, "dummy")),
            2 => Token::factory(array(null, 'echo')),
            3 => Token::factory(array(null, "dummy")),
            4 => Token::factory(array(null, ' ')),
            5 => Token::factory(array(null, '\$var')),
            6 => Token::factory(array(null, ';')),
        );
        $container = new TokenContainer($tokens);
        $iterator = new Iterator($container);

        foreach($tokens as $token) {
            $iterator->seekToToken($token);
            $this->assertTrue($iterator->valid());
            $this->assertSame($token, $iterator->current());
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::seek
     * @covers \PHP\Manipulator\TokenContainer\Iterator::<protected>
     */
    public function testSeekToTokenThrowsOutOfBoundsExceptionIfTokenNotInContainer()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);

        try {
            $iterator->seekToToken(new Token('Foo'));
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Token 'Foo' does not exist in this container", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::count
     */
    public function testCountable()
    {
        $container = new TokenContainer();
        $iterator = new Iterator($container);
        $this->assertCount(0, $iterator);

        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);
        $this->assertCount(5, $iterator);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::key
     * @covers \PHP\Manipulator\TokenContainer\Iterator::<protected>
     */
    public function testKeyThrowsOutOfBoundsExceptionIfIteratorIsNotValid()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);
        $iterator->seek(6);
        $iterator->next();

        try {
            $iterator->key();
            $this->fail('Expected exception not thrown');
        } catch (\OutOfBoundsException $e) {
            $this->assertEquals('Position not valid', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::current
     * @covers \PHP\Manipulator\TokenContainer\Iterator::<protected>
     */
    public function testCurrentThrowsOutOfBoundsExceptionIfIteratorIsNotValid()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);
        $iterator->seek(6);
        $iterator->next();

        try {
            $iterator->current();
            $this->fail('Expected exception not thrown');
        } catch (\OutOfBoundsException $e) {
            $this->assertEquals('Position not valid', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::seek
     * @covers \PHP\Manipulator\TokenContainer\Iterator::<protected>
     */
    public function testSeekThrowsOutOfBoundsExceptionIfIteratorIsNotValid()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);

        try {
            $iterator->seek(8);
            $this->fail('Expected exception not thrown');
        } catch (\OutOfBoundsException $e) {
            $this->assertEquals('Position not found', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::update
     */
    public function testUpdate()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);

        $this->assertCount(count($container), $iterator);

        $container[] = new Token('Foo', null);

        $this->assertCount(count($container)-1, $iterator);
        $iterator->update();
        $this->assertCount(count($container), $iterator);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::update
     */
    public function testUpdateWithoutSeek()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);
        $iterator->seekToToken($container[5]);
        $this->assertSame($iterator->current(), $container[5]);
        $iterator->update();
        $this->assertSame($iterator->current(), $container[0]);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::update
     */
    public function testUpdateWithSeek()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new Iterator($container);
        $iterator->seekToToken($container[5]);
        $this->assertSame($iterator->current(), $container[5]);
        $iterator->update($container[5]);
        $this->assertSame($iterator->current(), $container[5]);
    }

    public function testFluentInterfaces()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = $container->getIterator();
        $fluent = $iterator->seek(2);
        $this->assertSame($iterator, $fluent);
        $fluent = $iterator->seekToToken($container[2]);
        $this->assertSame($iterator, $fluent);
        $fluent = $iterator->update($container[2]);
        $this->assertSame($iterator, $fluent);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::getNext
     */
    public function testGetNext()
    {
        $t1 = Token::factory('Token1');
        $t2 = Token::factory('Token2');
        $container = new TokenContainer(array($t1,$t2));

        $iterator = $container->getIterator();

        $next = $iterator->getNext();

        $this->assertSame($t2, $next);
        $this->assertSame($t1, $iterator->current());
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::getNext
     */
    public function testGetNextReturnsNullIfThereIsNoNextToken()
    {
        $t1 = Token::factory('Token1');
        $container = new TokenContainer(array($t1));

        $iterator = $container->getIterator();

        $next = $iterator->getNext();

        $this->assertNull($next);
        $this->assertSame($t1, $iterator->current());
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::getPrevious
     */
    public function testGetPrevious()
    {
        $t1 = Token::factory('Token1');
        $t2 = Token::factory('Token2');
        $container = new TokenContainer(array($t1,$t2));

        $iterator = $container->getIterator();
        $iterator->seekToToken($t2);
        $previous = $iterator->getPrevious();

        $this->assertSame($t1, $previous);
        $this->assertSame($t2, $iterator->current());
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::getPrevious
     */
    public function testGetPreviousReturnsNullIfThereIsNoNextToken()
    {
        $t1 = Token::factory('Token1');
        $container = new TokenContainer(array($t1));

        $iterator = $container->getIterator();

        $previous = $iterator->getPrevious();

        $this->assertNull($previous);
        $this->assertSame($t1, $iterator->current());
    }
}
