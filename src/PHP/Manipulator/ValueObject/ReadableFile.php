<?php

namespace PHP\Manipulator\ValueObject;

use PHP\Manipulator\Exception\FileException;

class ReadableFile
{
    /**
     * @var string
     */
    private $filepath;

    /**
     * @param string $filepath
     */
    private function __construct($filepath)
    {
        $this->ensureIsValidFilePath($filepath);
        $this->filepath = $filepath;
    }

    /**
     * @param string $path
     *
     * @return ReadableFile
     */
    public static function createFromPath($path)
    {
        return new self($path);
    }

    /**
     * @return string
     */
    public function asString()
    {
        return $this->filepath;
    }

    /**
     * @param string $filepath
     *
     * @throws FileException
     */
    private function ensureIsValidFilePath($filepath)
    {
        if (!is_string($filepath)) {
            $type    = is_object($filepath) ? get_class($filepath) : gettype($filepath);
            $message = sprintf('Expected path to be string, got "%s"', $type);
            throw new FileException($message, FileException::EXPECTED_PATH_TO_BE_STRING);
        }

        if ($filepath === '') {
            $message = 'File path may not be empty';
            throw new FileException($message, FileException::EXPECTED_PATH_TO_NOT_BE_EMPTY);
        }

        if (!file_exists($filepath)) {
            $message = sprintf('Expected file "%s" to exist', $filepath);
            throw new FileException($message, FileException::EXPECTED_FILE_TO_EXIST);
        }

        if (!is_readable($filepath)) {
            $message = sprintf('Expected file "%s" to be readable', $filepath);
            throw new FileException($message, FileException::EXPECTED_FILE_TO_BE_READABLE);
        }

        if (!is_file($filepath)) {
            // @todo extend with actual file type
            $message = sprintf('Expected file "%s" to be a file', $filepath);
            throw new FileException($message, FileException::EXPECTED_TYPE_TO_BE_FILE);
        }
    }
}
