<?php

namespace Bundle\PaymentGatewayBundle\CacheWarmer;

/**
 * CacheWarmer. Creates logs directory
 */
class LogsWarmer implements \Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface
{

    protected $logsDir;

    public function __construct($logsDir)
    {
        $this->logsDir = $logsDir;
    }

    /**
     * @param string $cacheDir
     */
    public function warmUp($cacheDir)
    {
        if (!is_dir($this->logsDir)) {
            mkdir($this->logsDir, 0777, true);
        }

        if (!is_writeable($this->logsDir)) {
            chmod($this->logsDir, 0777);
        }
    }

    /**
     * @return Boolean
     */
    public function isOptional()
    {
        return false;
    }
}
