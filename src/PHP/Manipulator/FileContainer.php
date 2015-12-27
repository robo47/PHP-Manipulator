<?php

namespace PHP\Manipulator;

use PHP\Manipulator\ValueObject\ReadableFile;

/**
 * @todo Refactor to delegate to the container and implement Interface for container
 */
class FileContainer extends TokenContainer
{
    /**
     * @var ReadableFile
     */
    private $file = '';

    /**
     * @param ReadableFile $file
     * @param array        $tokens
     */
    protected function __construct(ReadableFile $file, array $tokens)
    {
        parent::__construct($tokens);
        $this->file = $file;
    }

    /**
     * @param ReadableFile $file
     *
     * @return FileContainer
     */
    public static function createFromFile(ReadableFile $file)
    {
        $code   = file_get_contents($file->asString());
        $tokens = token_get_all($code);

        return new self($file, self::createTokensFromArray($tokens));
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file->asString();
    }

    /**
     * Save code to a file
     *
     * @param string $file
     */
    public function saveTo($file)
    {
        file_put_contents($file, $this->toString());
    }

    public function save()
    {
        $this->saveTo($this->file->asString());
    }
}
