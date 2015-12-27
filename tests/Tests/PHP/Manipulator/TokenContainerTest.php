<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Exception\TokenContainerException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\ReverseTokenContainerIterator;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;
use ReflectionClass;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenContainer
 */
class TokenContainerTest extends TestCase
{
    public function testContainer()
    {
        $reflection = new ReflectionClass(TokenContainer::class);
        $this->assertTrue($reflection->implementsInterface('ArrayAccess'), 'Missing interface ArrayAccess');
        $this->assertTrue($reflection->implementsInterface('Countable'), 'Missing interface Countable');
        $this->assertTrue($reflection->implementsInterface('IteratorAggregate'), 'Missing interface IteratorAggregate');
    }

    public function testDefaultConstruct()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->assertSame([], $container->toArray(), 'Container missmatch');
        $this->assertCount(0, $container, 'Count missmatch');
    }

    public function testToArray()
    {
        $tokens = [
            0 => Token::createFromMixed(';'),
            1 => Token::createFromMixed('('),
            2 => Token::createFromMixed(')'),
        ];
        $container = TokenContainer::factory($tokens);
        $this->assertSame($tokens, $container->toArray(), 'Container missmatch');
    }

    public function testConstructWithTokens()
    {
        $tokens = [
            2      => Token::createFromMixed(';'),
            15     => Token::createFromMixed('('),
            'asdf' => Token::createFromMixed(')'),
        ];
        $container = TokenContainer::factory($tokens);
        $this->assertCount(3, $container, 'Count missmatch');
        $array = $container->toArray();
        $this->assertArrayHasKey(0, $array, 'Array misses key');
        $this->assertArrayHasKey(1, $array, 'Array misses key');
        $this->assertArrayHasKey(2, $array, 'Array misses key');
        $this->assertSame($tokens[2], $array[0], 'Element missmatch');
        $this->assertSame($tokens[15], $array[1], 'Element missmatch');
        $this->assertSame($tokens['asdf'], $array[2], 'Element missmatch');
    }

    public function testConstructWithCode()
    {
        $code      = '<?php echo $foo; ?>';
        $container = TokenContainer::factory($code);
        $this->assertCount(7, $container, 'Count missmatch');
    }

    public function testInsertAtOffset()
    {
        $array = [
            Token::createFromMixed('Blub'),
            Token::createFromMixed('Bla'),
            Token::createFromMixed('Foo'),
        ];
        $container = TokenContainer::factory($array);
        $newToken  = Token::createFromMixed('BaaFoo');
        $fluent    = $container->insertAtOffset(1, $newToken);

        $this->assertSame($fluent, $container, 'No fluent interface');

        $this->assertCount(4, $container);
        $this->assertSame($array[0], $container[0]);
        $this->assertSame($newToken, $container[1]);
        $this->assertSame($array[1], $container[2]);
        $this->assertSame($array[2], $container[3]);
    }

    public function testInsertAtOffsetThrowsExceptionOnInsertAfterNonExistingOffset()
    {
        $token      = Token::createFromMixed('Blub');
        $containter = TokenContainer::createEmptyContainer();

        $this->setExpectedException(
            TokenContainerException::class,
            '5',
            TokenContainerException::OFFSET_DOES_NOT_EXIST
        );

        $containter->insertAtOffset(5, $token);
    }

    public function testArrayAccess()
    {
        $token     = Token::createFromMixed('foo');
        $token2    = Token::createFromMixed('baa');
        $container = TokenContainer::createEmptyContainer();

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

    public function testOffsetSetWithNonIntegerOffsetThrowsException()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->setExpectedException(
            TokenContainerException::class,
            '',
            TokenContainerException::EXPECTED_OFFSET_TO_BE_INT
        );
        $container->offsetSet('offset', Token::createFromMixed('foo'));
    }

    public function testOffsetExistsWithNonIntegerOffsetThrowsException()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->setExpectedException(
            TokenContainerException::class,
            '',
            TokenContainerException::EXPECTED_OFFSET_TO_BE_INT
        );
        $container->offsetExists('offset');
    }

    public function testOffsetUnsetWithNonIntegerOffsetThrowsException()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->setExpectedException(
            TokenContainerException::class,
            '',
            TokenContainerException::EXPECTED_OFFSET_TO_BE_INT
        );
        $container->offsetUnset('offset');
    }

    public function testOffsetGetWithNonIntegerOffsetThrowsException()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->setExpectedException(
            TokenContainerException::class,
            '',
            TokenContainerException::EXPECTED_OFFSET_TO_BE_INT
        );
        $container->offsetGet('offset');
    }

    public function testCount()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->assertCount(0, $container, 'Wrong container count');
        $container[] = Token::createFromMixed('foo');
        $this->assertCount(1, $container, 'Wrong container count');
        $container[] = Token::createFromMixed('foo');
        $container[] = Token::createFromMixed('foo');
        $this->assertCount(3, $container, 'Wrong container count');
        $container->removeTokens($container->toArray());
        $this->assertCount(0, $container, 'Wrong container count');
    }

    public function testOffsetGetOnNotExistingOffsetThrowsException()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->setExpectedException(
            TokenContainerException::class,
            '5',
            TokenContainerException::OFFSET_DOES_NOT_EXIST
        );
        $container->offsetGet(5);
    }

    public function testGetOffsetByToken()
    {
        $array = [
            Token::createFromMixed('Blub'),
            Token::createFromMixed('Bla'),
            Token::createFromMixed('Foo'),
            Token::createFromMixed('BaaFoo'),
        ];
        $container = TokenContainer::factory($array);

        $this->assertSame(0, $container->getOffsetByToken($array[0]));
        $this->assertSame(1, $container->getOffsetByToken($array[1]));
        $this->assertSame(2, $container->getOffsetByToken($array[2]));
        $this->assertSame(3, $container->getOffsetByToken($array[3]));
    }

    public function testGetOffsetByTokenThrowsExceptionOnNonExistingToken()
    {
        $container = TokenContainer::createEmptyContainer();
        $token     = Token::createFromMixed('Blub');
        $this->setExpectedException(
            TokenContainerException::class,
            'Blub',
            TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER
        );
        $container->getOffsetByToken($token);
    }

    public function testContains()
    {
        $anotherToken = Token::createFromMixed('Blub');
        $array        = [
            Token::createFromMixed('Blub'),
            Token::createFromMixed('Bla'),
            Token::createFromMixed('Foo'),
            Token::createFromMixed('BaaFoo'),
        ];
        $container = TokenContainer::factory($array);

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

    public function testInsertTokenAfter()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::factory([$token1]);
        $container->insertTokenAfter($token1, $token2);
        $container->insertTokenAfter($token1, $token3);

        $this->assertSame(0, $container->getOffsetByToken($token1));
        $this->assertSame(1, $container->getOffsetByToken($token3));
        $this->assertSame(2, $container->getOffsetByToken($token2));
    }

    public function testInsertTokenBefore()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $token4    = Token::createFromMixed('Token4');
        $container = TokenContainer::factory([$token1, $token4]);
        $container->insertTokenBefore($token4, $token3);

        $this->assertCount(3, $container);

        $container->insertTokenBefore($token3, $token2);

        $this->assertCount(4, $container);

        $this->assertSame(0, $container->getOffsetByToken($token1));
        $this->assertSame(1, $container->getOffsetByToken($token2));
        $this->assertSame(2, $container->getOffsetByToken($token3));
        $this->assertSame(3, $container->getOffsetByToken($token4));
    }

    public function testInsertTokenBeforeOnFirstToken()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::factory([$token3]);
        $container->insertTokenBefore($token3, $token2);

        $this->assertCount(2, $container);

        $container->insertTokenBefore($token2, $token1);

        $this->assertCount(3, $container);

        $this->assertSame(0, $container->getOffsetByToken($token1));
        $this->assertSame(1, $container->getOffsetByToken($token2));
        $this->assertSame(2, $container->getOffsetByToken($token3));
    }

    public function testInsertTokenAfterThrowsExceptionIfAfterTokenNotExists()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::factory([$token1]);
        $this->setExpectedException(
            TokenContainerException::class,
            (string) $token2,
            TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER
        );
        $container->insertTokenAfter($token2, $token3);
    }

    public function testInsertTokenBeforeThrowsExceptionIfAfterTokenNotExists()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::factory([$token1]);
        $this->setExpectedException(
            TokenContainerException::class,
            (string) $token2,
            TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER
        );
        $container->insertTokenBefore($token2, $token3);
    }

    public function testInsertTokensBefore()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $token4    = Token::createFromMixed('Token4');
        $container = TokenContainer::factory([$token1, $token4]);
        $container->insertTokensBefore($token4, [$token2, $token3]);

        $this->assertCount(4, $container);

        $this->assertSame(0, $container->getOffsetByToken($token1));
        $this->assertSame(1, $container->getOffsetByToken($token2));
        $this->assertSame(2, $container->getOffsetByToken($token3));
        $this->assertSame(3, $container->getOffsetByToken($token4));
    }

    public function testInsertTokensBeforeOnFirst()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $token4    = Token::createFromMixed('Token4');
        $container = TokenContainer::factory([$token4]);
        $container->insertTokensBefore($token4, [$token1, $token2, $token3]);

        $this->assertCount(4, $container);

        $this->assertSame(0, $container->getOffsetByToken($token1));
        $this->assertSame(1, $container->getOffsetByToken($token2));
        $this->assertSame(2, $container->getOffsetByToken($token3));
        $this->assertSame(3, $container->getOffsetByToken($token4));
    }

    public function testInsertTokensAfterThrowsExceptionIfBeforeTokenNotExists()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $token4    = Token::createFromMixed('Token4');
        $container = TokenContainer::factory([$token1]);
        $this->setExpectedException(
            TokenContainerException::class,
            (string) $token2,
            TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER
        );
        $container->insertTokensBefore($token2, [$token3, $token4]);
    }

    public function testInsertTokensAfter()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::factory([$token1]);
        $container->insertTokensAfter($token1, [$token3, $token2]);

        $this->assertSame(0, $container->getOffsetByToken($token1));
        $this->assertSame(1, $container->getOffsetByToken($token3));
        $this->assertSame(2, $container->getOffsetByToken($token2));
    }

    public function testInsertTokensAfterThrowsExceptionIfAfterTokenNotExists()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $token4    = Token::createFromMixed('Token4');
        $container = TokenContainer::factory([$token1]);
        $this->setExpectedException(
            TokenContainerException::class,
            (string) $token2,
            TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER
        );
        $container->insertTokensAfter($token2, [$token3, $token4]);
    }

    public function testToString()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::createEmptyContainer();

        $this->assertSame('', $container->toString());

        $container[] = $token1;
        $this->assertSame('Token1', $container->toString());

        $container[] = $token3;
        $this->assertSame('Token1Token3', $container->toString());

        $container[] = $token2;
        $this->assertSame('Token1Token3Token2', $container->toString());
    }

    public function testMagicToString()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::createEmptyContainer();

        $this->assertSame('', (string) $container);

        $container[] = $token1;
        $this->assertSame('Token1', (string) $container);

        $container[] = $token3;
        $this->assertSame('Token1Token3', (string) $container);

        $container[] = $token2;
        $this->assertSame('Token1Token3Token2', (string) $container);
    }

    public function testGetContainersetArray()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::createEmptyContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $array = $container->toArray();
        $this->assertInternalType('array', $array);
        $this->assertCount(3, $array);
        $this->assertContains($token1, $array);
        $this->assertContains($token2, $array);
        $this->assertContains($token3, $array);

        $container->removeTokens($container->toArray());
        $array = $container->toArray();
        $this->assertInternalType('array', $array);
        $this->assertCount(0, $array);
    }

    public function testGetIterator()
    {
        $container = TokenContainer::createEmptyContainer();

        $iterator = $container->getIterator();
        $this->assertInstanceOf(TokenContainerIterator::class, $iterator);
    }

    public function testGetReverseIterator()
    {
        $container = TokenContainer::createEmptyContainer();

        $iterator = $container->getReverseIterator();
        $this->assertInstanceOf(ReverseTokenContainerIterator::class, $iterator);
    }

    public function testRetokenize()
    {
        $token0    = Token::createFromMixed([0 => T_OPEN_TAG, 1 => "<?php\n"]);
        $token1    = Token::createFromMixed([0 => T_WHITESPACE, 1 => " \n \n \n"]);
        $token2    = Token::createFromMixed([0 => T_WHITESPACE, 1 => " \t \n "]);
        $token3    = Token::createFromMixed([0 => T_CLOSE_TAG, 1 => '?>']);
        $container = TokenContainer::factory([$token0, $token1, $token2, $token3]);
        $container->retokenize();
        $this->assertCount(3, $container);

        $this->assertSame("<?php\n \n \n \n \t \n ?>", $container->toString());
    }

    public function testRemoveTokens()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::createEmptyContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $fluent = $container->removeTokens([$token3, $token1]);
        $this->assertSame($fluent, $container, 'No fluent interface');

        $this->assertCount(1, $container, 'Wrong count of Tokens in Container');

        $this->assertFalse($container->contains($token1), 'Container contains Token1');
        $this->assertFalse($container->contains($token3), 'Container contains Token3');
        $this->assertTrue($container->contains($token2), 'Container not contains Token2');
    }

    public function testRemoveToken()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $container = TokenContainer::createEmptyContainer();

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

    public function testGetNextToken()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $token4    = Token::createFromMixed('Token4');
        $container = TokenContainer::createEmptyContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $this->assertSame($token2, $container->getNextToken($token1), 'Wrong token');
        $this->assertSame($token3, $container->getNextToken($token2), 'Wrong token');
        $this->assertNull($container->getNextToken($token3), 'Found Token after last token');
        $this->assertNull($container->getNextToken($token4), 'Found Token which could not be found');
    }

    public function testGetPreviousToken()
    {
        $token1    = Token::createFromMixed('Token1');
        $token2    = Token::createFromMixed('Token2');
        $token3    = Token::createFromMixed('Token3');
        $token4    = Token::createFromMixed('Token4');
        $container = TokenContainer::createEmptyContainer();

        $container[] = $token1;
        $container[] = $token2;
        $container[] = $token3;

        $this->assertSame($token1, $container->getPreviousToken($token2), 'Wrong token');
        $this->assertSame($token2, $container->getPreviousToken($token3), 'Wrong token');
        $this->assertNull($container->getPreviousToken($token1), 'Found Token before first token');
        $this->assertNull($container->getPreviousToken($token4), 'Found Token which could not be found');
    }

    public function testRemoveTokensFromTo()
    {
        $t1        = Token::createFromMixed('Token1');
        $t2        = Token::createFromMixed('Token2');
        $t3        = Token::createFromMixed('Token3');
        $t4        = Token::createFromMixed('Token4');
        $t5        = Token::createFromMixed('Token5');
        $container = TokenContainer::factory([$t1, $t2, $t3, $t4, $t5]);

        $container->removeTokensFromTo($t2, $t4);
        $array = $container->toArray();
        $this->assertCount(2, $array);
        $this->assertContains($t1, $array);
        $this->assertContains($t5, $array);
    }

    public function testUpdateFromCode()
    {
        $container = TokenContainer::createEmptyContainer();
        $this->assertCount(0, $container, 'Count missmatch');
        $container->recreateContainerFromCode('<?php echo $foo; ?>');
        $this->assertCount(7, $container, 'Count missmatch');
    }
}
