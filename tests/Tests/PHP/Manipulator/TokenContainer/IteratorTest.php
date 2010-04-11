<?php

namespace Tests\PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
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
        $iterator = new TokenContainer\Iterator($container);

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
        $iterator = new TokenContainer\Iterator($container);

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
     * @covers \PHP\Manipulator\TokenContainer\Iterator::count
     */
    public function testCountable()
    {
        $container = new TokenContainer();
        $iterator = new TokenContainer\Iterator($container);
        $this->assertCount(0, $iterator);

        $container = $this->getTestContainerWithHoles();
        $iterator = new TokenContainer\Iterator($container);
        $this->assertCount(5, $iterator);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer\Iterator::key
     * @covers \PHP\Manipulator\TokenContainer\Iterator::<protected>
     */
    public function testKeyThrowsOutOfBoundsExceptionIfIteratorIsNotValid()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new TokenContainer\Iterator($container);
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
        $iterator = new TokenContainer\Iterator($container);
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
        $iterator = new TokenContainer\Iterator($container);

        try {
            $iterator->seek(8);
            $this->fail('Expected exception not thrown');
        } catch (\OutOfBoundsException $e) {
            $this->assertEquals('Position not found', $e->getMessage(), 'Wrong exception message');
        }
    }
}