<?php

namespace Tests\PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Command\License;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * @group Cli
 * @group Cli\Command
 * @group Cli\Command\License
 */
class LicenseTest extends \Tests\TestCase
{
    public function setUp()
    {
        ob_start();
    }

    public function tearDown()
    {
        ob_end_clean();
    }

    /**
     * @covers PHP\Manipulator\Cli\Command\License::execute
     */
    public function testExecute()
    {
        $command = new License();
        $command->execute(new ArgvInput(array()), new StreamOutput(fopen('php://output', 'w')));
        $output = ob_get_contents();
        $this->assertEquals('New BSD License' . PHP_EOL .
            PHP_EOL .
            'Copyright (c) 2010, Benjamin Steininger (Robo47)' . PHP_EOL .
            'All rights reserved.' . PHP_EOL .
            PHP_EOL .
            'Redistribution and use in source and binary forms, with or without' . PHP_EOL .
            'modification, are permitted provided that the following conditions are met:' . PHP_EOL .
            PHP_EOL .
            '    * Redistributions of source code must retain the above copyright notice,' . PHP_EOL .
            '      this list of conditions and the following disclaimer.' . PHP_EOL .
            '    * Redistributions in binary form must reproduce the above copyright' . PHP_EOL .
            '      notice, this list of conditions and the following disclaimer in the' . PHP_EOL .
            '      documentation and/or other materials provided with the distribution.' . PHP_EOL .
            '    * Neither the name of Benjamin Steininger nor the names of its contributors' . PHP_EOL .
            '      may be used to endorse or promote products derived from this software' . PHP_EOL .
            '      without specific prior written permission.' . PHP_EOL .
            PHP_EOL .
            'THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"' . PHP_EOL .
            'AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE' . PHP_EOL .
            'IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE' . PHP_EOL .
            'ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE' . PHP_EOL .
            'LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR' . PHP_EOL .
            'CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF' . PHP_EOL .
            'SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS' . PHP_EOL .
            'INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN' . PHP_EOL .
            'CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)' . PHP_EOL .
            'ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF' . PHP_EOL .
            'THE POSSIBILITY OF SUCH DAMAGE.' . PHP_EOL, $output);
    }
}
