<?php

require_once dirname(__FILE__) . '/../../TestHelper.php';
require_once 'PHP/Formatter/TokenContainer.php';

class PHP_Formatter_TokenContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testContainer()
    {
        
    }

    public function testDefaultConstruct()
    {

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
        $tokenArray = new PHP_Formatter_TokenContainer($array);
        $newToken = PHP_Formatter_Token::factory('BaaFoo');
        $fluent = $tokenArray->insertAtPosition(1, $newToken);

        $this->assertSame($fluent, $tokenArray, 'No fluent interface');

        $this->assertEquals(4, count($tokenArray));
        $this->assertSame($array[0], $tokenArray[0]);
        $this->assertSame($newToken, $tokenArray[1]);
        $this->assertSame($array[1], $tokenArray[2]);
        $this->assertSame($array[2], $tokenArray[3]);
    }
}