<?php

require_once 'PHP/Formatter/TokenContainer/ReverseIterator.php';

/**
 * @group TokenContainer_Iterator
 */
class PHP_Formatter_TokenContainer_ReverseIteratorTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_TokenContainer_ReverseIterator
     */
    public function testIteratorClass()
    {
        $reflection = new ReflectionClass('PHP_Formatter_TokenContainer_ReverseIterator');
        $this->assertTrue($reflection->isSubclassOf('PHP_Formatter_TokenContainer_Iterator'));
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
     * @covers PHP_Formatter_TokenContainer_ReverseIterator
     */
    public function testReverseIterator()
    {
        $container = $this->getTestContainerWithHoles();
        $iterator = new PHP_Formatter_TokenContainer_ReverseIterator($container);

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
}