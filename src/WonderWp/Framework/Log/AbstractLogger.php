<?php

namespace WonderWp\Framework\Log;

use DateTime;
use WonderWp\Framework\Log\LoggerInterface;

/**
 * Abstract Logger
 */
abstract class AbstractLogger implements LoggerInterface
{

    /** @var string * */
    const EMERGENCY = 'emergency';
    /** @var string * */
    const ALERT = 'alert';
    /** @var string * */
    const CRITICAL = 'critical';
    /** @var string * */
    const ERROR = 'error';
    /** @var string * */
    const WARNING = 'warning';
    /** @var string * */
    const NOTICE = 'notice';
    /** @var string * */
    const INFO = 'info';
    /** @var string * */
    const DEBUG = 'debug';
    /** @var SUCCESS * */
    const SUCCESS = 'success';

    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = [])
    {
        $this->log(AbstractLogger::EMERGENCY, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = [])
    {
        $this->log(AbstractLogger::ALERT, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = [])
    {
        $this->log(AbstractLogger::CRITICAL, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = [])
    {
        $this->log(AbstractLogger::ERROR, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = [])
    {
        $this->log(AbstractLogger::WARNING, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = [])
    {
        $this->log(AbstractLogger::NOTICE, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = [])
    {
        $this->log(AbstractLogger::INFO, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = [])
    {
        $this->log(AbstractLogger::INFO, $message, $context);
    }

    /**
     * @inheritDoc
     */
    abstract public function log($level, $message, array $context = []);

    /**
     * Adds the current date to the message.
     *
     * @param  string $message
     *
     * @return string
     */
    protected function withDate($message)
    {
        return sprintf(
            '%s %s',
            $message,
            (new DateTime('now'))->format('d/m/Y H:i:s')
        );
    }

    /**
     * Adds the current date to the message.
     *
     * @param  string $message
     *
     * @return string
     */
    protected function withTime($message)
    {
        return sprintf(
            '%s %s',
            $message,
            (new DateTime('now'))->format('H:i:s')
        );
    }
}
