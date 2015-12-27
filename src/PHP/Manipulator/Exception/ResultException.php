<?php

namespace PHP\Manipulator\Exception;

use Exception;

class ResultException extends Exception implements PHPManipulatorException
{
    const EMPTY_RESULT = 1;
}
