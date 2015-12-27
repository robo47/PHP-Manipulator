<?php

namespace Tests\PHP\Manipulator\TokenContainer;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\ReverseTokenContainerIterator;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;
use ReflectionClass;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenContainer\ReverseTokenContainerIterator
 */
class ReverseIteratorTest extends TestCase
{
    public function testIteratorClass()
    {
        $reflection = new ReflectionClass(ReverseTokenContainerIterator::class);
        $this->assertTrue($reflection->isSubclassOf(TokenContainerIterator::class));
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

    public function testReverseIterator()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new ReverseTokenContainerIterator($container);

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertSame(6, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertSame(5, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertSame(4, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertSame(2, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertSame(0, $iterator->key(), 'Wrong key');

        $iterator->next();

        $this->assertFalse($iterator->valid());

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[0], $iterator->current());
        $this->assertSame(0, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[2], $iterator->current());
        $this->assertSame(2, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[4], $iterator->current());
        $this->assertSame(4, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[5], $iterator->current());
        $this->assertSame(5, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertSame(6, $iterator->key(), 'Wrong key');

        $iterator->previous();

        $this->assertFalse($iterator->valid());

        $iterator->rewind();

        $this->assertTrue($iterator->valid());
        $this->assertSame($container[6], $iterator->current());
        $this->assertSame(6, $iterator->key(), 'Wrong key');
    }

    public function testUpdate()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator  = new ReverseTokenContainerIterator($container);

        $this->assertCount(count($container), $iterator);

        $container[] = Token::createFromValue('Foo');

        $this->assertCount(count($container) - 1, $iterator);
        $iterator->update();
        $this->assertCount(count($container), $iterator);
    }
}
