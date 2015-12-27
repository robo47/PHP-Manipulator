<?php

namespace PHP\Manipulator\Exception;

use Exception;

class FileException extends Exception implements PHPManipulatorException
{
    const EXPECTED_PATH_TO_BE_STRING    = 1;
    const EXPECTED_FILE_TO_BE_READABLE  = 2;
    const EXPECTED_FILE_TO_EXIST        = 3;
    const EXPECTED_TYPE_TO_BE_FILE      = 4;
    const EXPECTED_PATH_TO_NOT_BE_EMPTY = 5;
}
