<?php

namespace WonderWp\Framework\Log;

use WonderWp\Framework\Log\AbstractLogger;
use WonderWp\Framework\Log\LoggerInterface;

/**
 * Dumps the log directly.
 */
final class DirectOutputLogger extends AbstractLogger implements LoggerInterface
{

    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::EMERGENCY) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::ALERT) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::CRITICAL) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::ERROR) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::WARNING) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::NOTICE) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::INFO) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = array())
    {
        $this->print('[' . strtoupper(AbstractLogger::DEBUG) . ']', $message);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        $this->print('', $this->withDate($message));
    }

    /**
     * Prints the message.
     * @param string $prefix
     * @param string $message
     */
    private function print($prefix, $message)
    {
        print_r($this->withDate($prefix) . PHP_EOL . $message);
    }

}
