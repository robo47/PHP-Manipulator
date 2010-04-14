<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;

/**
 * @group Helper
 * @group Helper\NewlineDetector
 */
class NewlineDetectorTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function helperProvider()
    {
        $data = array();

        #0
        $data[] = array(
            new Token("/**\n * Foo\n */"),
            "\r", // default
            "\n", // expected
        );

        #1
        $data[] = array(
            new Token("/**\r * Foo\r */"),
            "\n",
            "\r",
        );

        #2
        $data[] = array(
            new Token("/**\r\n * Foo\r\n */"),
            "\n",
            "\r\n",
        );

        #3 Check the first found is returned
        $data[] = array(
            new Token("/**\r\n * Foo\n * Baa\r*/"),
            "\n",
            "\r\n",
        );

        #4 Check the first found is returned
        $data[] = array(
            new Token("/**\n * Foo\r\n * Baa\r*/"),
            "\r",
            "\n",
        );

        #5 Check the first found is returned
        $data[] = array(
            new Token("/**\r * Foo\n * Baa\r\n*/"),
            "\n",
            "\r",
        );

        return $data;
    }

    /**
     * @dataProvider helperProvider
     * @covers \PHP\Manipulator\Helper\NewlineDetector::getNewline
     */
    public function testManipulate($token, $defaultNewline, $expectedNewline)
    {
        $detector = new NewlineDetector();
        $actualNewline = $detector->getNewline($token);
        $this->assertEquals($expectedNewline, $actualNewline);
    }
    
    /**
     * @covers \PHP\Manipulator\Helper\NewlineDetector::__construct
     */
    public function testDefaultConstruct()
    {
        $detector = new NewlineDetector();
        $this->assertEquals("\n", $detector->getDefaultNewline());
    }
    
    /**
     * @covers \PHP\Manipulator\Helper\NewlineDetector::__construct
     */
    public function testConstructorSetsDefaultNewline()
    {
        $detector = new NewlineDetector("foo");
        $this->assertEquals("foo", $detector->getDefaultNewline());
    }

    /**
     * @covers \PHP\Manipulator\Helper\NewlineDetector::setDefaultNewline
     * @covers \PHP\Manipulator\Helper\NewlineDetector::getDefaultNewline
     */
    public function testSetDefaultNewlineGetDefaultNewline()
    {
        $detector = new NewlineDetector();
        $detector->setDefaultNewline("baa");
        $this->assertEquals("baa", $detector->getDefaultNewline());
    }
}