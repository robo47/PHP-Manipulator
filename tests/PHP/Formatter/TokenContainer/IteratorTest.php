<?php

require_once 'PHP/Formatter/TokenContainer/Iterator.php';

/**
 * @group TokenContainer_Iterator
 */
class PHP_Formatter_TokenContainer_IteratorTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_TokenContainer_Iterator
     */
    public function testIteratorClass()
    {
        $reflection = new ReflectionClass('PHP_Formatter_TokenContainer_Iterator');
        $this->assertTrue($reflection->isIterateable());
        $this->assertTrue($reflection->implementsInterface('SeekableIterator'));
        $this->assertTrue($reflection->implementsInterface('Countable'));
        $this->assertTrue($reflection->hasMethod('previous'));
    }

    /**
     * @return PHP_Formatter_TokenContainer
     */
    public function getTestContainerWithHoles()
    {
        $tokens = array(
            0 => PHP_Formatter_Token::factory(array(null, "<?php\n")),
            1 => PHP_Formatter_Token::factory(array(null, "dummy")),
            2 => PHP_Formatter_Token::factory(array(null, 'echo')),
            3 => PHP_Formatter_Token::factory(array(null, "dummy")),
            4 => PHP_Formatter_Token::factory(array(null, ' ')),
            5 => PHP_Formatter_Token::factory(array(null, '\$var')),
            6 => PHP_Formatter_Token::factory(array(null, ';')),
        );
        $container = new PHP_Formatter_TokenContainer($tokens);
        unset($container[1]);
        unset($container[3]);
        return $container;
    }

    /**
     * @covers PHP_Formatter_TokenContainer_Iterator
     */
    public function testIterator()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new PHP_Formatter_TokenContainer_Iterator($container);

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
     * @covers PHP_Formatter_TokenContainer_Iterator::seek
     */
    public function testSeek()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new PHP_Formatter_TokenContainer_Iterator($container);

        $iterator->next(); $iterator->next();

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
     * @covers PHP_Formatter_TokenContainer_Iterator::count
     */
    public function testCountable()
    {
        $container = new PHP_Formatter_TokenContainer();
        $iterator = new PHP_Formatter_TokenContainer_Iterator($container);
        $this->assertEquals(0, count($iterator));

        $container = $this->getTestContainerWithHoles();
        $iterator = new PHP_Formatter_TokenContainer_Iterator($container);
        $this->assertEquals(5, count($iterator));
    }
}