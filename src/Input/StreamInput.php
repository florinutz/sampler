<?php
namespace Sampler\Input;

class StreamInput implements InputInterface
{
    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var resource
     */
    protected $stream;

    /**
     * @var int
     */
    private $readLimit;

    /**
     * examples: 'php://stdin', 'php://temp', 'php://memory'
     *
     * @param resource $stream
     * @param int|null $readLimit
     */
    public function __construct($stream, ?int $readLimit)
    {
        if (!is_resource($stream) || get_resource_type($stream) != 'stream') {
            throw new \InvalidArgumentException('First argument should be a stream');
        }
        if (!in_array(stream_get_meta_data($stream)['mode'], ['w+b', 'r'])) {
            throw new \InvalidArgumentException('Stream is not readable');
        }
        $this->stream = $stream;
        $this->readLimit = $readLimit;
    }

    /**
     * @return string
     */
    function readCharacter(): string
    {
        if (!$this->hasMoreCharacters()) {
            throw new \UnderflowException('No more chars left');
        }
        $this->count++;

        return fgetc($this->stream);
    }

    /**
     * @return bool
     */
    function hasMoreCharacters(): bool
    {
        if (($this->readLimit && ($this->count > $this->readLimit)) || feof($this->stream)) {
            fclose($this->stream);
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    function countReadCharacters(): int
    {
        return $this->count;
    }
}
