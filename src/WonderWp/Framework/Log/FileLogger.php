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
        $this->write('[EMERGENCY]', $message);
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = array())
    {
        $this->write('[ALERT]', $message);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = array())
    {
        $this->write(['CRITICAL'], $message);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = array())
    {
        $this->write('[ERROR]', $message);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = array())
    {
        $this->write('[WARNING]', $message);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = array())
    {
        $this->write('[NOTICE]', $message);
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = array())
    {
        $this->write('[INFO]', $message);
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = array())
    {
        $this->write('[DEBUG]', $message);
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
