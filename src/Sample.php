<?php
namespace Sampler;

use Sampler\Input\InputInterface;

class Sample
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var string[] the actual sample, an array of chars
     */
    protected $sample;

    /**
     * @var int
     */
    protected $sampleSize;

    /**
     * @var
     */
    protected $nextSaveIndex = 0;

    public const BLANK_VALUE = null;

    /**
     * @param int $sampleSize
     * @param InputInterface $input
     */
    function __construct(int $sampleSize, InputInterface $input)
    {
        $this->reset($sampleSize);
        $this->input = $input;
        $this->work();
    }

    /**
     * @throws \UnderflowException when no more chars can be returned from input
     */
    protected function work(): void
    {
        while ($this->getInput()->hasMoreCharacters()) {
            $char = $this->getInput()->readCharacter();
            if ($this->getInput()->countReadCharacters() === $this->nextSaveIndex + 1) {
                $this->save($char);
                $this->nextSaveIndex += $this->getOffsetForNextPosition();
            }
        }
    }

    /**
     * @param string $char
     */
    protected function save(string $char): void
    {
        $key = $this->getKeyToSaveOn();
        $this->sample[$key] = $char[0];
    }

    /**
     * @return int
     */
    protected function getKeyToSaveOn(): int
    {
        $key = key($this->sample);
        if (next($this->sample) === false) {
            reset($this->sample);
        }
        return $key;
    }

    /**
     * @param int $sampleSize
     */
    protected function reset(int $sampleSize): void
    {
        $this->sampleSize = $sampleSize;
        $this->sample = [];
        for ($i = 0; $i < $this->sampleSize; $i++) {
            $this->sample[$i] = static::BLANK_VALUE;
        }
        reset($this->sample);
    }

    /**
     * @return int
     */
    protected function getOffsetForNextPosition(): int
    {
        if ($this->getInput()->countReadCharacters() === 0) {
            return 1;
        }
        return 1 + intval($this->getInput()->countReadCharacters() / $this->sampleSize);
    }

    /**
     * @return string[]
     */
    public function getSample(): array
    {
        return $this->sample;
    }

    /**
     * @return int
     */
    public function getSampleSize(): int
    {
        return $this->sampleSize;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    function __toString(): string
    {
        return print_r($this->getSample(), true) . PHP_EOL .
            'parsed ' . $this->getInput()->countReadCharacters() . ' chars:';
    }
}
