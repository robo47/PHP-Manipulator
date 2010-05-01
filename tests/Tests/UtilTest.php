<?php

namespace Tests;

use Tests\Util;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Util
 */
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
            '[SIMPLE]                    |    1 | NULL | (',
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, '// foo', 5)),
            'T_COMMENT                   |    6 |    5 | //.foo',
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, "/*\n\t * föü\n\t */", 10)),
            'T_COMMENT                   |   15 |   10 | /*\n\t.*.föü\n\t.*/',
        );

        return $data;
    }

    /**
     * @covers \Tests\Util::dumpToken
     * @dataProvider tokenProvider
     * @param PHP\Manipulator\Token $token
     * @param string $dumpExpected
     */
    public function testDumpToken($token, $dumpExpected)
    {
        $dump = Util::dumpToken($token, false);
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
     * @covers \Tests\Util::dumpContainer
     * @dataProvider containerProvider
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
            new TokenContainer('<?php echo $foo; ?>'),
            new TokenContainer("<?php echo \$foo;\n?>"),
            '                         Tokens: 7                       |                      Tokens: 7                      ' . PHP_EOL .
            PHP_EOL .
            '0)  T_OPEN_TAG                  |    6 |    1 | <?php.   | T_OPEN_TAG                  |    6 |    1 | <?php.  ' . PHP_EOL .
            '1)  T_ECHO                      |    4 |    1 | echo     | T_ECHO                      |    4 |    1 | echo    ' . PHP_EOL .
            '2)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | .       ' . PHP_EOL .
            '3)  T_VARIABLE                  |    4 |    1 | $foo     | T_VARIABLE                  |    4 |    1 | $foo    ' . PHP_EOL .
            '4)  [SIMPLE]                    |    1 | NULL | ;        | [SIMPLE]                    |    1 | NULL | ;       ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '5)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | \n      ' . PHP_EOL .
            '6)  T_CLOSE_TAG                 |    2 |    1 | ?>       | T_CLOSE_TAG                 |    2 |    2 | ?>',
            false
        );

        # 1 strict
        $data[] = array(
            new TokenContainer('<?php echo $foo; ?>'),
            new TokenContainer("<?php echo \$foo;\n?>"),
            '                         Tokens: 7                       |                      Tokens: 7                      ' . PHP_EOL .
            PHP_EOL .
            '0)  T_OPEN_TAG                  |    6 |    1 | <?php.   | T_OPEN_TAG                  |    6 |    1 | <?php.  ' . PHP_EOL .
            '1)  T_ECHO                      |    4 |    1 | echo     | T_ECHO                      |    4 |    1 | echo    ' . PHP_EOL .
            '2)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | .       ' . PHP_EOL .
            '3)  T_VARIABLE                  |    4 |    1 | $foo     | T_VARIABLE                  |    4 |    1 | $foo    ' . PHP_EOL .
            '4)  [SIMPLE]                    |    1 | NULL | ;        | [SIMPLE]                    |    1 | NULL | ;       ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '5)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | \n      ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '6)  T_CLOSE_TAG                 |    2 |    1 | ?>       | T_CLOSE_TAG                 |    2 |    2 | ?>',
            true
        );

        #2
        $data[] = array(
            new TokenContainer("<?php echo \$foo; ?>"),
            new TokenContainer("<?php echo \$foo;\n echo \$baa;\n?>"),
            '                         Tokens: 7                       |                      Tokens: 12                     ' . PHP_EOL .
            PHP_EOL .
            '0)  T_OPEN_TAG                  |    6 |    1 | <?php.   | T_OPEN_TAG                  |    6 |    1 | <?php.  ' . PHP_EOL .
            '1)  T_ECHO                      |    4 |    1 | echo     | T_ECHO                      |    4 |    1 | echo    ' . PHP_EOL .
            '2)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | .       ' . PHP_EOL .
            '3)  T_VARIABLE                  |    4 |    1 | $foo     | T_VARIABLE                  |    4 |    1 | $foo    ' . PHP_EOL .
            '4)  [SIMPLE]                    |    1 | NULL | ;        | [SIMPLE]                    |    1 | NULL | ;       ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '5)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    2 |    1 | \n.     ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '6)  T_CLOSE_TAG                 |    2 |    1 | ?>       | T_ECHO                      |    4 |    2 | echo    ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '7)                                                       | T_WHITESPACE                |    1 |    2 | .       ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '8)                                                       | T_VARIABLE                  |    4 |    2 | $baa    ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '9)                                                       | [SIMPLE]                    |    1 | NULL | ;       ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '10)                                                      | T_WHITESPACE                |    1 |    2 | \n      ' . PHP_EOL .
            '####### NEXT IS DIFFERENT ## ' . PHP_EOL .
            '11)                                                      | T_CLOSE_TAG                 |    2 |    3 | ?>',
            false
        );

        return $data;
    }

    /**
     * @covers \Tests\Util::compareContainers
     * @dataProvider containerCompareProvider
     */
    public function testCompareContainer($expectedContainer, $actualContainer, $expectedDump, $strict)
    {
        $dump = Util::compareContainers($expectedContainer, $actualContainer, $strict);
        $this->assertSame($expectedDump, $dump, 'Dump does not match');
    }

    /**
     * @covers \Tests\Util::compareResults
     */
    public function testCompareResults()
    {
        $this->markTestIncomplete('not implemented yet');
    }
}