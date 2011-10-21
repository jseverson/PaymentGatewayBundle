<?php

namespace Bundle\PaymentGatewayBundle;

class PaymentLogger
{
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function log($message)
    {
        $currentTimestamp = new \DateTime('now');
        $message = $currentTimestamp->format('Y-m-d g:i:sa') . " " . $message ."\n";
        $logFileName = $this->config['logsPath'] . $currentTimestamp->format('d_m_Y') . ".log";
        if (file_exists($logFileName)) {
            $logFile = \fopen($logFileName, 'a');
        } else {
            $logFile = \fopen($logFileName, 'x');
            chmod($logFileName, 0777);
        }
        \fwrite($logFile, $message);
        \fclose($logFile);
    }
}
