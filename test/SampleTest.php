<?php
namespace Sampler\Test;

use PHPUnit\Framework\TestCase;
use Sampler\Input\StreamInput;
use Sampler\Input\StringInput;
use Sampler\Sample;

class SampleTest extends TestCase
{
    function testNotEnoughIncomingChars()
    {
        $sampleArray = (new Sample(3, new StringInput('ab')))->getSample();
        $this->assertEquals(1, $this->countBlanks($sampleArray), 'One element not filled');
    }

    /**
     * @dataProvider getEasyData
     *
     * @param string $string
     * @param int $sampleLen
     */
    function testEachCharacter(string $string, int $sampleLen)
    {
        $sampleArray = (new Sample($sampleLen, new StringInput($string)))->getSample();
        $valuesCounts = array_count_values($sampleArray);
        foreach ($valuesCounts as $char => $count) {
            $this->assertTrue(strlen($char) === 1, 'It\'s a char');
            $this->assertTrue(strpos($string, $char) !== false, 'Char in string');
            $this->assertTrue($count <= substr_count($string, $char), 'Number of appearances is legit');
        }
    }

    function getEasyData(): array
    {
        return [
            [ implode(range('a', 'z')), 25 ]
        ];
    }

    protected function countBlanks(array $array): int
    {
        $i = 0;
        foreach ($array as $item) {
            if ($item === Sample::BLANK_VALUE) {
                $i++;
            }
        }
        return $i;
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
