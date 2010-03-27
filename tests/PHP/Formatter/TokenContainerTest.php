<?php

require_once 'PHP/Formatter/TokenContainer.php';

class PHP_Formatter_TokenContainerTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_TokenContainer
     */
    public function testContainer()
    {
        $reflection = new ReflectionClass('PHP_Formatter_TokenContainer');
        $this->assertTrue($reflection->implementsInterface('ArrayAccess'), 'Missing interface ArrayAccess');
        $this->assertTrue($reflection->implementsInterface('Countable'), 'Missing interface Countable');
        $this->assertTrue($reflection->implementsInterface('IteratorAggregate'), 'Missing interface IteratorAggregate');
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::__construct
     */
    public function testDefaultConstruct()
    {
        $container = new PHP_Formatter_TokenContainer();
        $this->assertEquals(array(), $container->getContainer(), 'Container missmatch');
        $this->assertEquals(0, count($container), 'Count missmatch');
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::__construct
     */
    public function testConstructWithTokens()
    {
        $tokens = array(
            2 => PHP_Formatter_Token::factory(';'),
            15 => PHP_Formatter_Token::factory('('),
            'asdf' => PHP_Formatter_Token::factory(')'),
        );
        $container = new PHP_Formatter_TokenContainer($tokens);
        $this->assertEquals(3, count($container), 'Count missmatch');
        $array = $container->getContainer();
        $this->assertArrayHasKey(0, $array, 'Array misses key');
        $this->assertArrayHasKey(1, $array, 'Array misses key');
        $this->assertArrayHasKey(2, $array, 'Array misses key');
        $this->assertEquals($tokens[2], $array[0], 'Element missmatch');
        $this->assertEquals($tokens[15], $array[1], 'Element missmatch');
        $this->assertEquals($tokens['asdf'], $array[2], 'Element missmatch');
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::__construct
     * @covers PHP_Formatter_Exception
     */
    public function testConstructWithInvalidTokens()
    {
        $tokens = array('blub', 'bla');
        try {
            $container = new PHP_Formatter_TokenContainer($tokens);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals('TokenContainer only allows adding PHP_Formatter_Token', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::insertAtPosition
     */
    public function testInsertAtPosition()
    {
        $array = array(
            PHP_Formatter_Token::factory('Blub'),
            PHP_Formatter_Token::factory('Bla'),
            PHP_Formatter_Token::factory('Foo'),
        );
        $container = new PHP_Formatter_TokenContainer($array);
        $newToken = PHP_Formatter_Token::factory('BaaFoo');
        $fluent = $container->insertAtPosition(1, $newToken);

        $this->assertSame($fluent, $container, 'No fluent interface');

        $this->assertEquals(4, count($container));
        $this->assertSame($array[0], $container[0]);
        $this->assertSame($newToken, $container[1]);
        $this->assertSame($array[1], $container[2]);
        $this->assertSame($array[2], $container[3]);
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::offsetSet
     * @covers PHP_Formatter_TokenContainer::offsetGet
     * @covers PHP_Formatter_TokenContainer::offsetUnset
     * @covers PHP_Formatter_TokenContainer::offsetExists
     */
    public function testArrayAccess()
    {
        $token = PHP_Formatter_Token::factory('foo');
        $token2 = PHP_Formatter_Token::factory('baa');
        $container = new PHP_Formatter_TokenContainer();

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
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::offsetSet
     * @covers PHP_Formatter_Exception
     */
    public function testOffsetSetWithNonIntegerOffsetThrowsException()
    {
        $container = new PHP_Formatter_TokenContainer();
        try {
            $container->offsetSet('offset', PHP_Formatter_Token::factory('foo'));
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::offsetExists
     * @covers PHP_Formatter_Exception
     */
    public function testOffsetExistsWithNonIntegerOffsetThrowsException()
    {
        $container = new PHP_Formatter_TokenContainer();
        try {
            $container->offsetExists('offset');
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::offsetUnset
     * @covers PHP_Formatter_Exception
     */
    public function testOffsetUnsetWithNonIntegerOffsetThrowsException()
    {
        $container = new PHP_Formatter_TokenContainer();
        try {
            $container->offsetUnset('offset');
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::offsetGet
     * @covers PHP_Formatter_Exception
     */
    public function testOffsetGetWithNonIntegerOffsetThrowsException()
    {
        $container = new PHP_Formatter_TokenContainer();
        try {
            $container->offsetGet('offset');
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals('TokenContainer only allows integers as offset', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::count
     * @covers PHP_Formatter_Exception
     */
    public function testCount()
    {
        $container = new PHP_Formatter_TokenContainer();
        $this->assertEquals(0, count($container), 'Wrong container count');
        $container[] = PHP_Formatter_Token::factory('foo');
        $this->assertEquals(1, count($container), 'Wrong container count');
        $container[] = PHP_Formatter_Token::factory('foo');
        $container[] = PHP_Formatter_Token::factory('foo');
        $this->assertEquals(3, count($container), 'Wrong container count');
        $container->setContainer(array());
        $this->assertEquals(0, count($container), 'Wrong container count');
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::offsetGet
     * @covers PHP_Formatter_Exception
     */
    public function testOffsetGetOnNotExistingOffsetThrowsException()
    {
        $container = new PHP_Formatter_TokenContainer();
        try {
            $container->offsetGet(5);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("Offset '5' does not exist", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::getPositionForOffset
     * @covers PHP_Formatter_Exception
     */
    public function testGetPositionForOffset()
    {
        $array = array(
            PHP_Formatter_Token::factory('Blub'),
            PHP_Formatter_Token::factory('Bla'),
            PHP_Formatter_Token::factory('Foo'),
            PHP_Formatter_Token::factory('BaaFoo'),
        );
        $container = new PHP_Formatter_TokenContainer($array);

        $this->assertEquals(0, $container->getPositionForOffset(0));
        $this->assertEquals(1, $container->getPositionForOffset(1));
        $this->assertEquals(2, $container->getPositionForOffset(2));
        $this->assertEquals(3, $container->getPositionForOffset(3));

        unset($container[1]);

        $this->assertEquals(0, $container->getPositionForOffset(0));
        $this->assertEquals(1, $container->getPositionForOffset(2));
        $this->assertEquals(2, $container->getPositionForOffset(3));
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::getPositionForOffset
     * @covers PHP_Formatter_Exception
     */
    public function testGetPositionForOffsetThrowsExceptionOnNonExistingOffset()
    {
        $container = new PHP_Formatter_TokenContainer();
        try {
            $container->getPositionForOffset(5);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("Offset '5' does not exist", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::getOffsetByToken
     * @covers PHP_Formatter_Exception
     */
    public function testGetOffsetByToken()
    {
        $array = array(
            PHP_Formatter_Token::factory('Blub'),
            PHP_Formatter_Token::factory('Bla'),
            PHP_Formatter_Token::factory('Foo'),
            PHP_Formatter_Token::factory('BaaFoo'),
        );
        $container = new PHP_Formatter_TokenContainer($array);

        $this->assertEquals(0, $container->getOffsetByToken($array[0]));
        $this->assertEquals(1, $container->getOffsetByToken($array[1]));
        $this->assertEquals(2, $container->getOffsetByToken($array[2]));
        $this->assertEquals(3, $container->getOffsetByToken($array[3]));
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::getOffsetByToken
     * @covers PHP_Formatter_Exception
     */
    public function testGetOffsetByTokenThrowsExceptionOnNonExistingToken()
    {
        $container = new PHP_Formatter_TokenContainer();
        $token = PHP_Formatter_Token::factory('Blub');
        try {
            $container->getOffsetByToken($token);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("Token '$token' does not exist in this container", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::<protected>
     * @covers PHP_Formatter_TokenContainer::contains
     */
    public function testContains()
    {
        $anotherToken = PHP_Formatter_Token::factory('Blub');
        $array = array(
            PHP_Formatter_Token::factory('Blub'),
            PHP_Formatter_Token::factory('Bla'),
            PHP_Formatter_Token::factory('Foo'),
            PHP_Formatter_Token::factory('BaaFoo'),
        );
        $container = new PHP_Formatter_TokenContainer($array);

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
     * @covers PHP_Formatter_TokenContainer::insertTokenAfter
     */
    public function testInsertTokenAfter()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer(array($token1));
        $container->insertTokenAfter($token1, $token2);
        $container->insertTokenAfter($token1, $token3);

        $this->assertEquals(0, $container->getOffsetByToken($token1));
        $this->assertEquals(1, $container->getOffsetByToken($token3));
        $this->assertEquals(2, $container->getOffsetByToken($token2));
    }

    /**
     * @covers PHP_Formatter_TokenContainer::insertTokenAfter
     * @covers PHP_Formatter_Exception
     */
    public function testInsertTokenAfterThrowsExceptionIfAfterTokenNotExists()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer(array($token1));

        try {
            $container->insertTokenAfter($token2, $token3);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("Container does not contain Token: $token2", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::insertTokensAfter
     */
    public function testInsertTokensAfter()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer(array($token1));
        $container->insertTokensAfter($token1,  array($token3, $token2));

        $this->assertEquals(0, $container->getOffsetByToken($token1));
        $this->assertEquals(1, $container->getOffsetByToken($token3));
        $this->assertEquals(2, $container->getOffsetByToken($token2));
    }

    /**
     * @covers PHP_Formatter_TokenContainer::insertTokensAfter
     * @covers PHP_Formatter_Exception
     */
    public function testInsertTokensAfterThrowsExceptionIfAfterTokenNotExists()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $token4 = PHP_Formatter_Token::factory('Token4');
        $container = new PHP_Formatter_TokenContainer(array($token1));

        try {
            $container->insertTokensAfter($token2, array($token3, $token4));
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("Container does not contain Token: $token2", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::toString
     * @covers PHP_Formatter_Exception
     */
    public function testToString()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer();

        $this->assertEquals('', $container->toString());

        $container[] = $token1;
        $this->assertEquals('Token1', $container->toString());

        $container[] = $token3;
        $this->assertEquals('Token1Token3', $container->toString());

        $container[] = $token2;
        $this->assertEquals('Token1Token3Token2', $container->toString());
    }

    /**
     * @covers PHP_Formatter_TokenContainer::__toString
     */
    public function test__ToString()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer();

        $this->assertEquals('', (string)$container);

        $container[] = $token1;
        $this->assertEquals('Token1', (string)$container);

        $container[] = $token3;
        $this->assertEquals('Token1Token3', (string)$container);

        $container[] = $token2;
        $this->assertEquals('Token1Token3Token2', (string)$container);
    }

    /**
     * @covers PHP_Formatter_TokenContainer::setContainer
     * @covers PHP_Formatter_TokenContainer::getContainer
     */
    public function testGetContainerSetContainer()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $array = $container->getContainer();
        $this->assertType('array', $array);
        $this->assertEquals(3, count($array));
        $this->assertContains($token1, $array);
        $this->assertContains($token2, $array);
        $this->assertContains($token3, $array);

        $container->setContainer(array());
        $array = $container->getContainer();
        $this->assertType('array', $array);
        $this->assertEquals(0, count($array));
    }

    /**
     * @covers PHP_Formatter_TokenContainer::getIterator
     */
    public function testGetIterator()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $iterator = $container->getIterator();
        /* @var $iterator ArrayIterator */
        $this->assertType('ArrayIterator', $iterator);
        $this->assertEquals($container->getContainer(), $iterator->getArrayCopy());
    }

    /**
     * @covers PHP_Formatter_TokenContainer::createFromCode
     */
    public function testCreateFromCode()
    {
        $code = '<?php echo 1; ?>';
        $container = PHP_Formatter_TokenContainer::createFromCode($code);

        $this->assertEquals(7, count($container));
        $this->assertEquals('<?php echo 1; ?>', $container->toString());

        $tokens = token_get_all($code);

        $i = 0;
        foreach($tokens as $token) {
            $tokenObject = PHP_Formatter_Token::factory($token);
            $this->assertTrue($tokenObject->equals($container[$i], true));
            $i++;
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::retokenize
     */
    public function testRetokenize()
    {
        $token0 = PHP_Formatter_Token::factory(array(0 => T_OPEN_TAG, 1 => "<?php\n"));
        $token1 = PHP_Formatter_Token::factory(array(0 => T_WHITESPACE, 1 => " \n \n \n"));
        $token2 = PHP_Formatter_Token::factory(array(0 => T_WHITESPACE, 1 => " \t \n "));
        $token3 = PHP_Formatter_Token::factory(array(0 => T_CLOSE_TAG, 1 => "?>"));
        $container = new PHP_Formatter_TokenContainer(array($token0, $token1, $token2, $token3));
        $container->retokenize();
        $this->assertEquals(3, count($container));
        $string = $container->toString();
        $this->assertEquals("<?php\n \n \n \n \t \n ?>", $container->toString());
    }

    /**
     * @covers PHP_Formatter_TokenContainer::createTokenArrayFromCode
     */
    public function testCreateTokenArrayFromCode()
    {
        $code = '<?php echo 1; ?>';
        $array = PHP_Formatter_TokenContainer::createTokenArrayFromCode($code);

        $this->assertEquals(7, count($array));

        $tokens = token_get_all($code);

        $i = 0;
        foreach($tokens as $token) {
            $tokenObject = PHP_Formatter_Token::factory($token);
            $this->assertTrue($tokenObject->equals($array[$i], true));
            $i++;
        }
    }

    /**
     * @covers PHP_Formatter_TokenContainer::removeTokens
     */
    public function testRemoveTokens()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $fluent = $container->removeTokens(array($token3, $token1));
        $this->assertSame($fluent, $container, 'No fluent interface');

        $this->assertEquals(1, count($container), 'Wrong count of Tokens in Container');

        $this->assertFalse($container->contains($token1), 'Container contains Token1');
        $this->assertFalse($container->contains($token3), 'Container contains Token3');
        $this->assertTrue($container->contains($token2), 'Container not contains Token2');
    }

    /**
     * @covers PHP_Formatter_TokenContainer::removeToken
     */
    public function testRemoveToken()
    {
        $token1 = PHP_Formatter_Token::factory('Token1');
        $token2 = PHP_Formatter_Token::factory('Token2');
        $token3 = PHP_Formatter_Token::factory('Token3');
        $container = new PHP_Formatter_TokenContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $fluent = $container->removeToken($token2);
        $this->assertSame($fluent, $container, 'No fluent interface');

        $this->assertEquals(2, count($container), 'Wrong count of Tokens in Container');

        $this->assertTrue($container->contains($token1), 'Container contains Token1');
        $this->assertTrue($container->contains($token3), 'Container contains Token3');
        $this->assertFalse($container->contains($token2), 'Container not contains Token2');
    }
}