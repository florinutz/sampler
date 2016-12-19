<?php
namespace Sampler\Test;

use PHPUnit\Framework\TestCase;
use Sampler\Input\StreamInput;
use Sampler\Input\StringInput;
use Sampler\Sample;


class InputTest extends TestCase
{
    function testStringInput()
    {
        $len = 3;
        $sample = new Sample($len, new StringInput(implode('', range('a', 'z'))));
        $this->expectAFullSample($sample, $len);
    }

    /**
     * @dataProvider streamsProvider
     *
     * @param resource $stream an open stream
     * @param int $readLimit limit of chars to read
     */
    function testStream($stream, int $readLimit)
    {
        $length = 10;
        $sample = new Sample($length, new StreamInput($stream, $readLimit));
        $this->expectAFullSample($sample, $length);
    }

    /**
     * @return resource[] array of open streams next to their read limits
     */
    function streamsProvider(): array
    {
        $streams = ['md5' => [ $this->stringToStream(md5(microtime())), 32 ]];

        $urandom = fopen('/dev/urandom', 'r');
        stream_filter_append($urandom, 'convert.base64-encode');

        if ($urandom !== false) { // not on win
            $streams['urandom'] = [ $urandom, 4096 ];
        }

        return $streams;
    }

    /**
     * String to stream
     *
     * @param string $string
     * @return resource
     */
    protected function stringToStream(string $string)
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        rewind($stream);
        return $stream;
    }

    /**
     * @param $sample
     * @param $length
     */
    protected function expectAFullSample(Sample $sample, $length)
    {
        $this->assertTrue(is_array($sample->getSample()), 'Sample is array');
        $this->assertEquals($length, count($sample->getSample()), 'Sample has the correct size');
        $this->assertFalse(in_array(Sample::BLANK_VALUE, $sample->getSample()), 'Sample is full');
    }
}
