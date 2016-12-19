<?php
namespace Sampler\Input;

class StringInput implements InputInterface
{
    /**
     * @var string
     */
    protected $chars;

    /**
     * @var int
     */
    protected $count = 0;

    public function __construct(string $source)
    {
        $this->chars = str_split($source);
    }

    function readCharacter(): string
    {
        if (empty($this->chars)) {
            throw new \UnderflowException('No more characters left to parse');
        }
        $this->count++;
        return array_shift($this->chars);
    }

    function hasMoreCharacters(): bool
    {
        return !empty($this->chars);
    }

    function countReadCharacters(): int
    {
        return $this->count;
    }
}
