<?php

namespace Tests\PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer\ReverseIterator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenContainer
 * @group TokenContainer\ReverseIterator
 */
class ReverseIteratorTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\TokenContainer\ReverseIterator
     */
    public function testIteratorClass()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\TokenContainer\ReverseIterator');
        $this->assertTrue($reflection->isSubclassOf('PHP\Manipulator\TokenContainer\Iterator'));
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
     * @covers \PHP\Manipulator\TokenContainer\ReverseIterator
     */
    public function testReverseIterator()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new TokenContainer\ReverseIterator($container);

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertEquals(6, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertEquals(5, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertEquals(4, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertEquals(2, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertEquals(0, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertFalse($iterator->valid());

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertEquals(0, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertEquals(2, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertEquals(4, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertEquals(5, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertEquals(6, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertFalse($iterator->valid());

        $iterator->rewind();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertEquals(6, $iterator->key(), 'Wrong key');
    }


    /**
     * @covers \PHP\Manipulator\TokenContainer\ReverseIterator::reInit
     */
    public function testReInit()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new ReverseIterator($container);

        $this->assertCount(count($container), $iterator);

        $container[] = new Token('Foo', null);

        $this->assertCount(count($container)-1, $iterator);
        $iterator->reInit();
        $this->assertCount(count($container), $iterator);
    }
}