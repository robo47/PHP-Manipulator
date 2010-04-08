<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Util;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class UtilTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function tokenProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory('('),
            '[SIMPLE]                    |    1 | NULL | (' . PHP_EOL,
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, '// foo', 5)),
            'T_COMMENT                   |    6 |    5 | //.foo' . PHP_EOL,
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, "/*\n\t * föü\n\t */", 10)),
            'T_COMMENT                   |   15 |   10 | /*\n\t.*.föü\n\t.*/' . PHP_EOL,
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Util::dumpToken
     * @dataProvider tokenProvider
     * @param PHP\Manipulator\Token $token
     * @param string $dumpExpected
     */
    public function testDumpToken($token, $dumpExpected)
    {
        $dump = Util::dumpToken($token);
        $this->assertEquals($dumpExpected, $dump, 'dump does not match');
    }

    /**
     * @return array
     */
    public function arrayProvider()
    {
        $data = array();

        #0
        $data[] = array(
            array(
                str_repeat('ä', 10),
                str_repeat('ä', 20),
                str_repeat('ä', 30),
            ),
            30
        );

        #1
        $data[] = array(
            array(
                str_repeat('ä', 30),
                str_repeat('ä', 20),
                str_repeat('ä', 10),
            ),
            30
        );

        #2
        $data[] = array(
            array(
                str_repeat('ä', 20),
                str_repeat('ä', 30),
                str_repeat('ä', 10),
            ),
            30
        );

        #2
        $data[] = array(
            array(
                str_repeat('ä', 20),
                str_repeat('ä', 30),
                str_repeat('ä', 10),
            ),
            30
        );

        return $data;
    }

    /**
     *
     * @covers \PHP\Manipulator\Util::getLongestLineLength
     * @dataProvider arrayProvider
     * @param array $array
     * @param integer $longest
     */
    public function testGetLongestLineLength($array, $expectedLongest)
    {
        $longest = Util::getLongestLineLength($array);
        $this->assertSame($expectedLongest, $longest, 'Length does not match');
    }

    /**
     * @return array
     */
    public function containerProvider()
    {
        $data = array();

        $data[] = array(
            new TokenContainer('<?php echo $foo; ?>'),
            'Token                       |  LEN | LINE | VALUE' . PHP_EOL . PHP_EOL .
                'T_OPEN_TAG                  |    6 |    1 | <?php.' . PHP_EOL .
                'T_ECHO                      |    4 |    1 | echo' . PHP_EOL .
                'T_WHITESPACE                |    1 |    1 | .' . PHP_EOL .
                'T_VARIABLE                  |    4 |    1 | $foo' . PHP_EOL .
                '[SIMPLE]                    |    1 | NULL | ;' . PHP_EOL .
                'T_WHITESPACE                |    1 |    1 | .' . PHP_EOL .
                'T_CLOSE_TAG                 |    2 |    1 | ?>',
        );

        return $data;
    }

    /**
     *
     * @covers \PHP\Manipulator\Util::dumpContainer
     * @dataProvider containerProvider
     * @param PHP\Manipulator\TokenContainer $container
     * @param string $expectedDump
     */
    public function testDumpContainer($container, $expectedDump)
    {
        $dump = Util::dumpContainer($container);
        $this->assertSame($expectedDump, $dump, 'Dump does not match');
    }

    /**
     * @return array
     */
    public function containerCompareProvider()
    {
        $data = array();

        #0
        $data[] = array(
            new TokenContainer("<?php echo \$foo; ?>"),
            new TokenContainer("<?php echo \$foo;\n?>"),
            '                       Tokens: 7                         |                       Tokens: 7                        ' . PHP_EOL .
                '     Token                       |  LEN | LINE | VALUE   |  Token                       |  LEN | LINE | VALUE' . PHP_EOL .
                '                                                         |  ' . PHP_EOL .
                '  1) T_OPEN_TAG                  |    6 |    1 | <?php.  |  T_OPEN_TAG                  |    6 |    1 | <?php.' . PHP_EOL .
                '  2) T_ECHO                      |    4 |    1 | echo    |  T_ECHO                      |    4 |    1 | echo' . PHP_EOL .
                '  3) T_WHITESPACE                |    1 |    1 | .       |  T_WHITESPACE                |    1 |    1 | .' . PHP_EOL .
                '  4) T_VARIABLE                  |    4 |    1 | $foo    |  T_VARIABLE                  |    4 |    1 | $foo' . PHP_EOL .
                '  5) [SIMPLE]                    |    1 | NULL | ;       |  [SIMPLE]                    |    1 | NULL | ;' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                '  6) T_WHITESPACE                |    1 |    1 | .       |  T_WHITESPACE                |    1 |    1 | \n' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                '  7) T_CLOSE_TAG                 |    2 |    1 | ?>      |  T_CLOSE_TAG                 |    2 |    2 | ?>' . PHP_EOL,
        );

        #1
        $data[] = array(
            new TokenContainer("<?php echo \$foo; ?>"),
            new TokenContainer("<?php echo \$foo;\n echo \$baa;\n?>"),
            '                       Tokens: 7                         |                       Tokens: 12                       ' . PHP_EOL .
                '     Token                       |  LEN | LINE | VALUE   |  Token                       |  LEN | LINE | VALUE' . PHP_EOL .
                '                                                         |  ' . PHP_EOL .
                '  1) T_OPEN_TAG                  |    6 |    1 | <?php.  |  T_OPEN_TAG                  |    6 |    1 | <?php.' . PHP_EOL .
                '  2) T_ECHO                      |    4 |    1 | echo    |  T_ECHO                      |    4 |    1 | echo' . PHP_EOL .
                '  3) T_WHITESPACE                |    1 |    1 | .       |  T_WHITESPACE                |    1 |    1 | .' . PHP_EOL .
                '  4) T_VARIABLE                  |    4 |    1 | $foo    |  T_VARIABLE                  |    4 |    1 | $foo' . PHP_EOL .
                '  5) [SIMPLE]                    |    1 | NULL | ;       |  [SIMPLE]                    |    1 | NULL | ;' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                '  6) T_WHITESPACE                |    1 |    1 | .       |  T_WHITESPACE                |    2 |    1 | \n.' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                '  7) T_CLOSE_TAG                 |    2 |    1 | ?>      |  T_ECHO                      |    4 |    2 | echo' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                '  8)                                                     |  T_WHITESPACE                |    1 |    2 | .' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                '  9)                                                     |  T_VARIABLE                  |    4 |    2 | $baa' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                ' 10)                                                     |  [SIMPLE]                    |    1 | NULL | ;' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                ' 11)                                                     |  T_WHITESPACE                |    1 |    2 | \n' . PHP_EOL .
                '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
                ' 12)                                                     |  T_CLOSE_TAG                 |    2 |    3 | ?>' . PHP_EOL,
        );

        return $data;
    }

    /**
     *
     * @covers \PHP\Manipulator\Util::compareContainers
     * @dataProvider containerCompareProvider
     * @param PHP\Manipulator\TokenContainer $first
     * @param PHP\Manipulator\TokenContainer $second
     * @param string $expectedDump
     */
    public function testCompaeContainer($first, $second, $expectedDump)
    {
        $dump = Util::compareContainers($first, $second);
        $this->assertSame($expectedDump, $dump, 'Dump does not match');
    }
}