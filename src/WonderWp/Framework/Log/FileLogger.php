<?php

namespace WonderWp\Framework\Log;

use WonderWp\Framework\Log\AbstractLogger;
use WonderWp\Framework\Log\LoggerInterface;

/**
 * Logs messages in a file
 */
final class FileLogger extends AbstractLogger implements LoggerInterface
{

    /** @var string **/
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::EMERGENCY) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::ALERT) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::CRITICAL) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::ERROR) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::WARNING) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::NOTICE) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::INFO) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = array())
    {
        $this->write('[' . strtoupper(AbstractLogger::DEBUG) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        $this->write($message);
    }

    /**
     * Appends the content to the end of the file.
     * @param string $prefix
     * @param string $content
     */
    private function write($prefix, $content)
    {
        $file = fopen($this->path, 'a');

        fwrite($file, $this->withDate($prefix) . PHP_EOL . $content . PHP_EOL);

        fclose($file);
    }

}
