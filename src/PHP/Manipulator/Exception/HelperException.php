<?php

namespace PHP\Manipulator\Exception;

use Exception;

class HelperException extends Exception implements PHPManipulatorException
{
    const UNSUPPORTED_BRACE_EXCEPTION                      = 1;
    const FINDER_IS_NOT_INSTANCE_OF_TOKENFINDER            = 2;
    const ACTION_IS_NOT_INSTANCE_OF_ACTION                 = 3;
    const MANIPULATOR_IS_NOT_INSTANCE_OF_TOKEN_MANIPULATOR = 4;
    const CONSTRAINT_IS_NOT_INSTANCE_OF_TOKEN_CONSTRAINT   = 5;
    const OPTION_NOT_FOUND                                 = 6;
    const START_OFFSET_BEHIND_END_OFFSET                   = 7;
    const FROM_NOT_FOUND_IN_CONTAINER                      = 8;
    const TO_NOT_FOUND_IN_CONTAINER                        = 9;
}
