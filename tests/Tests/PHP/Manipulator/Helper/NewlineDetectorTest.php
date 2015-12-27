<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Helper\NewlineDetector
 */
class NewlineDetectorTest extends TestCase
{
    /**
     * @return array
     */
    public function tokenProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromValue("/**\n * Foo\n */"),
            "\r", // default
            "\n", // expected
        ];

        #1
        $data[] = [
            Token::createFromValue("/**\r * Foo\r */"),
            "\n",
            "\r",
        ];

        #2
        $data[] = [
            Token::createFromValue("/**\r\n * Foo\r\n */"),
            "\n",
            "\r\n",
        ];

        #3 Check the first found is returned
        $data[] = [
            Token::createFromValue("/**\r\n * Foo\n * Baa\r*/"),
            "\n",
            "\r\n",
        ];

        #4 Check the first found is returned
        $data[] = [
            Token::createFromValue("/**\n * Foo\r\n * Baa\r*/"),
            "\r",
            "\n",
        ];

        #5 Check the first found is returned
        $data[] = [
            Token::createFromValue("/**\r * Foo\n * Baa\r\n*/"),
            "\n",
            "\r",
        ];

        #6
        $data[] = [
            Token::createFromValue('/** Foo */'),
            "\r",
            "\r",
        ];

        return $data;
    }

    /**
     * @dataProvider tokenProvider
     *
     * @param Token  $token
     * @param string $defaultNewline
     * @param string $expectedNewline
     */
    public function testGetNewlineFromToken(Token $token, $defaultNewline, $expectedNewline)
    {
        $detector      = new NewlineDetector($defaultNewline);
        $actualNewline = $detector->getNewlineFromToken($token);
        $this->assertSame($expectedNewline, $actualNewline);
    }

    /**
     * @return array
     */
    public function containerProvider()
    {
        $data = [];

        #0
        $data[] = [
            TokenContainer::factory("<?php\r\necho \$foo;// foo\r\necho \$baa;\r\n ?>"),
            "\r", // default
            "\r\n", // expected
        ];

        #1
        $data[] = [
            TokenContainer::factory("<?php\necho \$foo;// foo\necho \$baa;\n ?>"),
            "\r", // default
            "\n", // expected
        ];

        #2
        $data[] = [
            TokenContainer::factory("<?php\recho \$foo;// foo\recho \$baa;\r ?>"),
            "\n", // default
            "\r", // expected
        ];

        #3
        $data[] = [
            TokenContainer::factory('<?php echo $foo; ?>'),
            "\r", // default
            "\r", // expected
        ];

        return $data;
    }

    /**
     * @dataProvider containerProvider
     *
     * @param TokenContainer $container
     * @param string         $defaultNewline
     * @param string         $expectedNewline
     */
    public function testGetNewlineFromContainer(TokenContainer $container, $defaultNewline, $expectedNewline)
    {
        $detector      = new NewlineDetector($defaultNewline);
        $actualNewline = $detector->getNewlineFromContainer($container);
        $this->assertSame($expectedNewline, $actualNewline);
    }
}
