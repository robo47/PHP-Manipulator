<?php

namespace Tests;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\Result;

/**
 * @covers Tests\Util
 */
class UtilTest extends TestCase
{
    /**
     * @return array
     */
    public function tokenProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed('('),
            '[SIMPLE]                    |    1 | NULL | (',
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_COMMENT, '// foo', 5]),
            'T_COMMENT                   |    6 |    5 | //.foo',
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_COMMENT, "/*\n\t * föü\n\t */", 10]),
            'T_COMMENT                   |   15 |   10 | /*\n\t.*.föü\n\t.*/',
        ];

        return $data;
    }

    /**
     * @dataProvider tokenProvider
     *
     * @param Token  $token
     * @param string $dumpExpected
     */
    public function testDumpToken($token, $dumpExpected)
    {
        $dump = Util::dumpToken($token);
        $this->assertSame($dumpExpected, $dump, 'dump does not match');
    }

    /**
     * @return array
     */
    public function arrayProvider()
    {
        $data = [];

        #0
        $data[] = [
            [
                str_repeat('ä', 10),
                str_repeat('ä', 20),
                str_repeat('ä', 30),
            ],
            30,
        ];

        #1
        $data[] = [
            [
                str_repeat('ä', 30),
                str_repeat('ä', 20),
                str_repeat('ä', 10),
            ],
            30,
        ];

        #2
        $data[] = [
            [
                str_repeat('ä', 20),
                str_repeat('ä', 30),
                str_repeat('ä', 10),
            ],
            30,
        ];

        #2
        $data[] = [
            [
                str_repeat('ä', 20),
                str_repeat('ä', 30),
                str_repeat('ä', 10),
            ],
            30,
        ];

        return $data;
    }

    /**
     * @return array
     */
    public function containerProvider()
    {
        $data = [];

        $data[] = [
            TokenContainer::factory('<?php echo $foo; ?>'),
            'Token                       |  LEN | LINE | VALUE'.PHP_EOL.PHP_EOL.
            'T_OPEN_TAG                  |    6 |    1 | <?php.'.PHP_EOL.
            'T_ECHO                      |    4 |    1 | echo'.PHP_EOL.
            'T_WHITESPACE                |    1 |    1 | .'.PHP_EOL.
            'T_VARIABLE                  |    4 |    1 | $foo'.PHP_EOL.
            '[SIMPLE]                    |    1 | NULL | ;'.PHP_EOL.
            'T_WHITESPACE                |    1 |    1 | .'.PHP_EOL.
            'T_CLOSE_TAG                 |    2 |    1 | ?>',
        ];

        return $data;
    }

    /**
     * @dataProvider containerProvider
     *
     * @param TokenContainer $container
     * @param string         $expectedDump
     */
    public function testDumpContainer(TokenContainer $container, $expectedDump)
    {
        $dump = Util::dumpContainer($container);
        $this->assertSame($expectedDump, $dump, 'Dump does not match');
    }

    /**
     * @return array
     */
    public function containerCompareProvider()
    {
        $data = [];

        #0
        $data[] = [
            TokenContainer::factory('<?php echo $foo; ?>'),
            TokenContainer::factory("<?php echo \$foo;\n?>"),
            '                         Tokens: 7                       |                      Tokens: 7                      '.PHP_EOL.
            PHP_EOL.
            '0)  T_OPEN_TAG                  |    6 |    1 | <?php.   | T_OPEN_TAG                  |    6 |    1 | <?php.  '.PHP_EOL.
            '1)  T_ECHO                      |    4 |    1 | echo     | T_ECHO                      |    4 |    1 | echo    '.PHP_EOL.
            '2)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | .       '.PHP_EOL.
            '3)  T_VARIABLE                  |    4 |    1 | $foo     | T_VARIABLE                  |    4 |    1 | $foo    '.PHP_EOL.
            '4)  [SIMPLE]                    |    1 | NULL | ;        | [SIMPLE]                    |    1 | NULL | ;       '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '5)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | \n      '.PHP_EOL.
            '6)  T_CLOSE_TAG                 |    2 |    1 | ?>       | T_CLOSE_TAG                 |    2 |    2 | ?>',
            false,
        ];

        # 1 strict
        $data[] = [
            TokenContainer::factory('<?php echo $foo; ?>'),
            TokenContainer::factory("<?php echo \$foo;\n?>"),
            '                         Tokens: 7                       |                      Tokens: 7                      '.PHP_EOL.
            PHP_EOL.
            '0)  T_OPEN_TAG                  |    6 |    1 | <?php.   | T_OPEN_TAG                  |    6 |    1 | <?php.  '.PHP_EOL.
            '1)  T_ECHO                      |    4 |    1 | echo     | T_ECHO                      |    4 |    1 | echo    '.PHP_EOL.
            '2)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | .       '.PHP_EOL.
            '3)  T_VARIABLE                  |    4 |    1 | $foo     | T_VARIABLE                  |    4 |    1 | $foo    '.PHP_EOL.
            '4)  [SIMPLE]                    |    1 | NULL | ;        | [SIMPLE]                    |    1 | NULL | ;       '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '5)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | \n      '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '6)  T_CLOSE_TAG                 |    2 |    1 | ?>       | T_CLOSE_TAG                 |    2 |    2 | ?>',
            true,
        ];

        #2
        $data[] = [
            TokenContainer::factory('<?php echo $foo; ?>'),
            TokenContainer::factory("<?php echo \$foo;\n echo \$baa;\n?>"),
            '                         Tokens: 7                       |                      Tokens: 12                     '.PHP_EOL.
            PHP_EOL.
            '0)  T_OPEN_TAG                  |    6 |    1 | <?php.   | T_OPEN_TAG                  |    6 |    1 | <?php.  '.PHP_EOL.
            '1)  T_ECHO                      |    4 |    1 | echo     | T_ECHO                      |    4 |    1 | echo    '.PHP_EOL.
            '2)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    1 |    1 | .       '.PHP_EOL.
            '3)  T_VARIABLE                  |    4 |    1 | $foo     | T_VARIABLE                  |    4 |    1 | $foo    '.PHP_EOL.
            '4)  [SIMPLE]                    |    1 | NULL | ;        | [SIMPLE]                    |    1 | NULL | ;       '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '5)  T_WHITESPACE                |    1 |    1 | .        | T_WHITESPACE                |    2 |    1 | \n.     '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '6)  T_CLOSE_TAG                 |    2 |    1 | ?>       | T_ECHO                      |    4 |    2 | echo    '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '7)                                                       | T_WHITESPACE                |    1 |    2 | .       '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '8)                                                       | T_VARIABLE                  |    4 |    2 | $baa    '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '9)                                                       | [SIMPLE]                    |    1 | NULL | ;       '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '10)                                                      | T_WHITESPACE                |    1 |    2 | \n      '.PHP_EOL.
            '####### NEXT IS DIFFERENT ## '.PHP_EOL.
            '11)                                                      | T_CLOSE_TAG                 |    2 |    3 | ?>',
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider containerCompareProvider
     *
     * @param TokenContainer $expectedContainer
     * @param TokenContainer $actualContainer
     * @param bool           $expectedDump
     * @param bool           $strict
     */
    public function testCompareContainer(
        TokenContainer $expectedContainer,
        TokenContainer $actualContainer,
        $expectedDump,
        $strict
    ) {
        $dump = Util::compareContainers($expectedContainer, $actualContainer, $strict);
        $this->assertSame($expectedDump, $dump, 'Dump does not match');
    }

    /**
     * @return array
     */
    public function resultsCompareProvider()
    {
        $data = [];

        $t1 = Token::createFromValue('blub');
        $t2 = Token::createFromValue('bla');

        # 0
        $data[] = [
            Result::factory([$t1]),
            Result::factory([$t1]),
            '                       expected (1)                    |                     actual(1)                     '.PHP_EOL.
            PHP_EOL.
            '0)  [SIMPLE]                    |    4 | NULL | blub   | [SIMPLE]                    |    4 | NULL | blub  '.PHP_EOL,
        ];

        # 1
        $data[] = [
            Result::factory([]),
            Result::factory([$t1]),
            '                       expected (0)                    |                     actual(1)                     '.PHP_EOL.PHP_EOL.
            '####### NEXT IS DIFFERENT ##'.PHP_EOL.
            '0)                                                     | [SIMPLE]                    |    4 | NULL | blub  '.PHP_EOL,
        ];

        # 2
        $data[] = [
            Result::factory([$t1]),
            Result::factory([]),
            '                       expected (1)                    |                     actual(0)                     '.PHP_EOL.PHP_EOL.
            '####### NEXT IS DIFFERENT ##'.PHP_EOL.
            '0)  [SIMPLE]                    |    4 | NULL | blub   |                                                   '.PHP_EOL,
        ];

        # 3
        $data[] = [
            Result::factory([$t1]),
            Result::factory([$t1, $t2]),
            '                       expected (1)                    |                     actual(2)                     '.PHP_EOL.
            PHP_EOL.
            '0)  [SIMPLE]                    |    4 | NULL | blub   | [SIMPLE]                    |    4 | NULL | blub  '.PHP_EOL.
            '####### NEXT IS DIFFERENT ##'.PHP_EOL.
            '1)                                                     | [SIMPLE]                    |    3 | NULL | bla   '.PHP_EOL,
        ];

        return $data;
    }

    /**
     * @dataProvider resultsCompareProvider
     *
     * @param Result $expectedResult
     * @param Result $actualResult
     * @param string $compareString
     */
    public function testCompareResults(Result $expectedResult, Result $actualResult, $compareString)
    {
        $this->assertSame($compareString, Util::compareResults($expectedResult, $actualResult));
    }
}
