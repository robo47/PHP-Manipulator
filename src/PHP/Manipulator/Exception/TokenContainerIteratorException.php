<?php

namespace PHP\Manipulator\Exception;

use Exception;

class TokenContainerIteratorException extends Exception implements PHPManipulatorException
{
    const CURRENT_POSITION_IS_INVALID = 1;
    const NO_NEXT_TOKEN               = 2;
    const NO_PREVIOUS_TOKEN           = 3;
}
