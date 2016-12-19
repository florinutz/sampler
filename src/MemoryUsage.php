<?php
namespace Sampler;

class MemoryUsage
{
    /**
     * @var int
     */
    protected $peak;

    public function __construct()
    {
        $this->refreshData();
    }


    /**
     * @return void
     */
    public function refreshData() : void
    {
        $this->peak = memory_get_usage();
    }

    /**
     * @param int $bytes
     *
     * @return string
     */
    protected static function getHumanReadable(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return round($bytes / 1048576, 2) . ' MB';
        }
    }

    function __toString()
    {
        return static::getHumanReadable($this->peak);
    }
}
