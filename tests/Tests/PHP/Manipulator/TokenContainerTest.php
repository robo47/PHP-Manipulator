<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenContainer
 * @todo create methods for setting up a default test container + method for returning the tokens
 */
class TokenContainerTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\TokenContainer
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\TokenContainer');
        $this->assertTrue($reflection->implementsInterface('ArrayAccess'), 'Missing interface ArrayAccess');
        $this->assertTrue($reflection->implementsInterface('Countable'), 'Missing interface Countable');
        $this->assertTrue($reflection->implementsInterface('IteratorAggregate'), 'Missing interface IteratorAggregate');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::__construct
     */
    public function testDefaultConstruct()
    {
        $container = new TokenContainer();
        $this->assertEquals(array(), $container->getContainer(), 'Container missmatch');
        $this->assertCount(0, $container, 'Count missmatch');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::__construct
     */
    public function testConstructWithTokens()
    {
        $tokens = array(
            2 => Token::factory(';'),
            15 => Token::factory('('),
            'asdf' => Token::factory(')'),
        );
        $container = new TokenContainer($tokens);
        $this->assertCount(3, $container, 'Count missmatch');
        $array = $container->getContainer();
        $this->assertArrayHasKey(0, $array, 'Array misses key');
        $this->assertArrayHasKey(1, $array, 'Array misses key');
        $this->assertArrayHasKey(2, $array, 'Array misses key');
        $this->assertEquals($tokens[2], $array[0], 'Element missmatch');
        $this->assertEquals($tokens[15], $array[1], 'Element missmatch');
        $this->assertEquals($tokens['asdf'], $array[2], 'Element missmatch');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::__construct
     */
    public function testConstructWithCode()
    {
        $code = '<?php echo $foo; ?>';
        $container = new TokenContainer($code);
        $this->assertCount(7, $container, 'Count missmatch');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::__construct
     * @covers \Exception
     */
    public function testConstructWithInvalidTokens()
    {
        $tokens = array('blub', 'bla');
        try {
            new TokenContainer($tokens);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('TokenContainer only allows adding PHP\Manipulator\Token', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::insertAtOffset
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     */
    public function testInsertAtOffset()
    {
        $array = array(
            Token::factory('Blub'),
            Token::factory('Bla'),
            Token::factory('Foo'),
        );
        $container = new TokenContainer($array);
        $newToken = Token::factory('BaaFoo');
        $fluent = $container->insertAtOffset(1, $newToken);

        $this->assertSame($fluent, $container, 'No fluent interface');

        $this->assertCount(4, $container);
        $this->assertSame($array[0], $container[0]);
        $this->assertSame($newToken, $container[1]);
        $this->assertSame($array[1], $container[2]);
        $this->assertSame($array[2], $container[3]);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::insertAtOffset
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     */
    public function testInsertAtOffsetThrowsExceptionOnInsertAfterNonExistingOffset()
    {
        $token = Token::factory('Blub');
        $containter = new TokenContainer();

        try {
            $containter->insertAtOffset(5, $token);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Offset '5' does not exist", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::offsetSet
     * @covers \PHP\Manipulator\TokenContainer::offsetGet
     * @covers \PHP\Manipulator\TokenContainer::offsetUnset
     * @covers \PHP\Manipulator\TokenContainer::offsetExists
     */
    public function testArrayAccess()
    {
        $token = Token::factory('foo');
        $token2 = Token::factory('baa');
        $container = new TokenContainer();

        // offsetSet
        $container[] = $token;

        // offsetSet
        $container[5] = $token2;

        // offsetGet
        $this->assertSame($token, $container[0]);

        // offsetGet
        $this->assertSame($token2, $container[5]);

        // offsetExists
        $this->assertTrue(isset($container[0]));

        // offsetUnset
        unset($container[0]);

        // offsetExists
        $this->assertFalse(isset($container[0]));
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::offsetSet
     * @covers \Exception
     */
    public function testOffsetSetWithNonIntegerOffsetThrowsException()
    {
        $container = new TokenContainer();
        try {
            $container->offsetSet('offset', Token::factory('foo'));
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::offsetExists
     * @covers \Exception
     */
    public function testOffsetExistsWithNonIntegerOffsetThrowsException()
    {
        $container = new TokenContainer();
        try {
            $container->offsetExists('offset');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::offsetUnset
     * @covers \Exception
     */
    public function testOffsetUnsetWithNonIntegerOffsetThrowsException()
    {
        $container = new TokenContainer();
        try {
            $container->offsetUnset('offset');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::offsetGet
     * @covers \Exception
     */
    public function testOffsetGetWithNonIntegerOffsetThrowsException()
    {
        $container = new TokenContainer();
        try {
            $container->offsetGet('offset');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::count
     * @covers \Exception
     */
    public function testCount()
    {
        $container = new TokenContainer();
        $this->assertCount(0, $container, 'Wrong container count');
        $container[] = Token::factory('foo');
        $this->assertCount(1, $container, 'Wrong container count');
        $container[] = Token::factory('foo');
        $container[] = Token::factory('foo');
        $this->assertCount(3, $container, 'Wrong container count');
        $container->setContainer(array());
        $this->assertCount(0, $container, 'Wrong container count');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::offsetGet
     * @covers \Exception
     */
    public function testOffsetGetOnNotExistingOffsetThrowsException()
    {
        $container = new TokenContainer();
        try {
            $container->offsetGet(5);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Offset '5' does not exist", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::getOffsetByToken
     * @covers \Exception
     */
    public function testGetOffsetByToken()
    {
        $array = array(
            Token::factory('Blub'),
            Token::factory('Bla'),
            Token::factory('Foo'),
            Token::factory('BaaFoo'),
        );
        $container = new TokenContainer($array);

        $this->assertEquals(0, $container->getOffsetByToken($array[0]));
        $this->assertEquals(1, $container->getOffsetByToken($array[1]));
        $this->assertEquals(2, $container->getOffsetByToken($array[2]));
        $this->assertEquals(3, $container->getOffsetByToken($array[3]));
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::getOffsetByToken
     * @covers \Exception
     */
    public function testGetOffsetByTokenThrowsExceptionOnNonExistingToken()
    {
        $container = new TokenContainer();
        $token = Token::factory('Blub');
        try {
            $container->getOffsetByToken($token);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Token '$token' does not exist in this container", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::<protected>
     * @covers \PHP\Manipulator\TokenContainer::contains
     */
    public function testContains()
    {
        $anotherToken = Token::factory('Blub');
        $array = array(
            Token::factory('Blub'),
            Token::factory('Bla'),
            Token::factory('Foo'),
            Token::factory('BaaFoo'),
        );
        $container = new TokenContainer($array);

        $this->assertTrue($container->contains($array[0]));
        $this->assertTrue($container->contains($array[1]));
        $this->assertTrue($container->contains($array[2]));
        $this->assertTrue($container->contains($array[3]));
        $this->assertFalse($container->contains($anotherToken));

        unset($container[1]);
        unset($container[2]);

        $this->assertFalse($container->contains($array[1]));
        $this->assertFalse($container->contains($array[2]));
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::insertTokenAfter
     */
    public function testInsertTokenAfter()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer(array($token1));
        $container->insertTokenAfter($token1, $token2);
        $container->insertTokenAfter($token1, $token3);

        $this->assertEquals(0, $container->getOffsetByToken($token1));
        $this->assertEquals(1, $container->getOffsetByToken($token3));
        $this->assertEquals(2, $container->getOffsetByToken($token2));
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::insertTokenAfter
     * @covers \Exception
     */
    public function testInsertTokenAfterThrowsExceptionIfAfterTokenNotExists()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer(array($token1));

        try {
            $container->insertTokenAfter($token2, $token3);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Container does not contain Token: $token2", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::insertTokensAfter
     */
    public function testInsertTokensAfter()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer(array($token1));
        $container->insertTokensAfter($token1, array($token3, $token2));

        $this->assertEquals(0, $container->getOffsetByToken($token1));
        $this->assertEquals(1, $container->getOffsetByToken($token3));
        $this->assertEquals(2, $container->getOffsetByToken($token2));
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::insertTokensAfter
     * @covers \Exception
     */
    public function testInsertTokensAfterThrowsExceptionIfAfterTokenNotExists()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $token4 = Token::factory('Token4');
        $container = new TokenContainer(array($token1));

        try {
            $container->insertTokensAfter($token2, array($token3, $token4));
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Container does not contain Token: $token2", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::toString
     * @covers \Exception
     */
    public function testToString()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer();

        $this->assertEquals('', $container->toString());

        $container[] = $token1;
        $this->assertEquals('Token1', $container->toString());

        $container[] = $token3;
        $this->assertEquals('Token1Token3', $container->toString());

        $container[] = $token2;
        $this->assertEquals('Token1Token3Token2', $container->toString());
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::__toString
     */
    public function test__ToString()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer();

        $this->assertEquals('', (string) $container);

        $container[] = $token1;
        $this->assertEquals('Token1', (string) $container);

        $container[] = $token3;
        $this->assertEquals('Token1Token3', (string) $container);

        $container[] = $token2;
        $this->assertEquals('Token1Token3Token2', (string) $container);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::setContainer
     * @covers \PHP\Manipulator\TokenContainer::getContainer
     */
    public function testGetContainerSetContainer()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $array = $container->getContainer();
        $this->assertType('array', $array);
        $this->assertCount(3, $array);
        $this->assertContains($token1, $array);
        $this->assertContains($token2, $array);
        $this->assertContains($token3, $array);

        $container->setContainer(array());
        $array = $container->getContainer();
        $this->assertType('array', $array);
        $this->assertCount(0, $array);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::getIterator
     */
    public function testGetIterator()
    {
        $container = new TokenContainer();

        $iterator = $container->getIterator();
        $this->assertType('PHP\Manipulator\TokenContainer\Iterator', $iterator);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::getReverseIterator
     */
    public function testGetReverseIterator()
    {
        $container = new TokenContainer();

        $iterator = $container->getReverseIterator();
        $this->assertType('PHP\Manipulator\TokenContainer\ReverseIterator', $iterator);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::retokenize
     */
    public function testRetokenize()
    {
        $token0 = Token::factory(array(0 => T_OPEN_TAG, 1 => "<?php\n"));
        $token1 = Token::factory(array(0 => T_WHITESPACE, 1 => " \n \n \n"));
        $token2 = Token::factory(array(0 => T_WHITESPACE, 1 => " \t \n "));
        $token3 = Token::factory(array(0 => T_CLOSE_TAG, 1 => "?>"));
        $container = new TokenContainer(array($token0, $token1, $token2, $token3));
        $container->retokenize();
        $this->assertCount(3, $container);

        $this->assertEquals("<?php\n \n \n \n \t \n ?>", $container->toString());
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::removeTokens
     */
    public function testRemoveTokens()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $fluent = $container->removeTokens(array($token3, $token1));
        $this->assertSame($fluent, $container, 'No fluent interface');

        $this->assertCount(1, $container, 'Wrong count of Tokens in Container');

        $this->assertFalse($container->contains($token1), 'Container contains Token1');
        $this->assertFalse($container->contains($token3), 'Container contains Token3');
        $this->assertTrue($container->contains($token2), 'Container not contains Token2');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::removeToken
     */
    public function testRemoveToken()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $container = new TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $fluent = $container->removeToken($token2);
        $this->assertSame($fluent, $container, 'No fluent interface');


        $this->assertCount(2, $container, 'Wrong count of Tokens in Container');

        $this->assertTrue($container->contains($token1), 'Container contains Token1');
        $this->assertTrue($container->contains($token3), 'Container contains Token3');
        $this->assertFalse($container->contains($token2), 'Container not contains Token2');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::getNextToken
     */
    public function testGetNextToken()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $token4 = Token::factory('Token4');
        $container = new TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $this->assertSame($token2, $container->getNextToken($token1), 'Wrong token');
        $this->assertSame($token3, $container->getNextToken($token2), 'Wrong token');
        $this->assertNull($container->getNextToken($token3), 'Found Token after last token');
        $this->assertNull($container->getNextToken($token4), 'Found Token which could not be found');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::getPreviousToken
     */
    public function testGetPreviousToken()
    {
        $token1 = Token::factory('Token1');
        $token2 = Token::factory('Token2');
        $token3 = Token::factory('Token3');
        $token4 = Token::factory('Token4');
        $container = new TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $this->assertSame($token1, $container->getPreviousToken($token2), 'Wrong token');
        $this->assertSame($token2, $container->getPreviousToken($token3), 'Wrong token');
        $this->assertNull($container->getPreviousToken($token1), 'Found Token before first token');
        $this->assertNull($container->getPreviousToken($token4), 'Found Token which could not be found');
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::getPreviousToken::removeTokensFromTo
     */
    public function testRemoveTokensFromTo()
    {
        $t1 = Token::factory('Token1');
        $t2 = Token::factory('Token2');
        $t3 = Token::factory('Token3');
        $t4 = Token::factory('Token4');
        $t5 = Token::factory('Token5');
        $container = new TokenContainer(array($t1,$t2, $t3, $t4, $t5));

        $container->removeTokensFromTo($t2, $t4);
        $array = $container->getContainer();
        $this->assertCount(2, $array);
        $this->assertContains($t1, $array);
        $this->assertContains($t5, $array);
    }

    /**
     * @covers \PHP\Manipulator\TokenContainer::reInitFromCode
     */
    public function testReInitFromCode()
    {
        $container = new TokenContainer();
        $this->assertCount(0, $container, 'Count missmatch');
        $container->reInitFromCode('<?php echo $foo; ?>');
        $this->assertCount(7, $container, 'Count missmatch');
    }
}