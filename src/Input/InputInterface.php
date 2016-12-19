<?php
namespace Sampler\Input;

/**
 * Sampler input sources
 *
 * @package Sampler\Input
 */
interface InputInterface
{
    /**
     * @throws \UnderflowException
     * @return string
     */
    function readCharacter(): string;

    /**
     * @return bool
     */
    function hasMoreCharacters(): bool;

    /**
     * @return int
     */
    function countReadCharacters(): int;
}
