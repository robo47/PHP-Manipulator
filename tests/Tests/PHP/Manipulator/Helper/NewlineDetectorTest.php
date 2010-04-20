<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\TokenContainer;
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
    public function tokenProvider()
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

        #6
        $data[] = array(
            new Token("/** Foo */"),
            "\r",
            "\r",
        );

        return $data;
    }

    /**
     * @dataProvider tokenProvider
     * @covers \PHP\Manipulator\Helper\NewlineDetector::getNewlineFromToken
     * @covers \PHP\Manipulator\Helper\NewlineDetector::<protected>
     */
    public function testGetNewlineFromToken($token, $defaultNewline, $expectedNewline)
    {
        $detector = new NewlineDetector($defaultNewline);
        $actualNewline = $detector->getNewlineFromToken($token);
        $this->assertEquals($expectedNewline, $actualNewline);
    }

    /**
     * @return array
     */
    public function containerProvider()
    {
        $data = array();

        #0
        $data[] = array(
            new TokenContainer("<?php\r\necho \$foo;// foo\r\necho \$baa;\r\n ?>"),
            "\r", // default
            "\r\n", // expected
        );

        #1
        $data[] = array(
            new TokenContainer("<?php\necho \$foo;// foo\necho \$baa;\n ?>"),
            "\r", // default
            "\n", // expected
        );

        #2
        $data[] = array(
            new TokenContainer("<?php\recho \$foo;// foo\recho \$baa;\r ?>"),
            "\n", // default
            "\r", // expected
        );

        #3
        $data[] = array(
            new TokenContainer("<?php echo \$foo; ?>"),
            "\r", // default
            "\r", // expected
        );

        return $data;
    }

    /**
     * @dataProvider containerProvider
     * @covers \PHP\Manipulator\Helper\NewlineDetector::getNewlineFromContainer
     * @covers \PHP\Manipulator\Helper\NewlineDetector::<protected>
     */
    public function testGetNewlineFromContainer($container, $defaultNewline, $expectedNewline)
    {
        $detector = new NewlineDetector($defaultNewline);
        $actualNewline = $detector->getNewlineFromContainer($container);
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