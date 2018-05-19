<?php

namespace Framework\Service;

use Framework\ServiceInterface;

class Logger implements ServiceInterface
{
    public const ERROR_LEVEL = 'error';
    public const WARNING_LEVEL = 'warning';
    public const INFO_LEVEL = 'info';

    private const LOG_FILENAME = LOG_DIR . '/app.log';

    /**
     * Logger constructor.
     */
    public function __construct()
    {
        $this->log(self::INFO_LEVEL, 'Logger instance has been created', false);
    }

    /**
     * @param string $msg
     * @return Logger
     */
    public function error(string $msg): Logger
    {
        $this->log(self::ERROR_LEVEL, $msg);
        return $this;
    }

    /**
     * @param string $msg
     * @return Logger
     */
    public function warning(string $msg): Logger
    {
        $this->log(self::WARNING_LEVEL, $msg);
        return $this;
    }

    /**
     * @param string $msg
     * @return Logger
     */
    public function info(string $msg): Logger
    {
        $this->log(self::INFO_LEVEL, $msg);
        return $this;
    }

    /**
     * @param string $level
     * @param string $msg
     * @param bool $shouldAppend
     */
    private function log(string $level, string $msg, bool $shouldAppend = true)
    {
        $logDate = new \DateTime();
        $msg = $logDate->format('d-m-Y H:i:s') . ' | ' . $msg . "\n";

        if ($shouldAppend) {
            file_put_contents(self::LOG_FILENAME, $msg, FILE_APPEND);
        } else {
            file_put_contents(self::LOG_FILENAME, $msg);
        }
    }
}