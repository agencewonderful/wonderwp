<?php

namespace WonderWp\Framework\Log;

use DateTime;
use WonderWp\Framework\Log\LoggerInterface;

/**
 * Abstract Logger
 */
abstract class AbstractLogger implements LoggerInterface
{

    /** @var string **/
    const EMERGENCY = 'emergency';
    /** @var string **/
    const ALERT     = 'alert';
    /** @var string **/
    const CRITICAL  = 'critical';
    /** @var string **/
    const ERROR     = 'error';
    /** @var string **/
    const WARNING   = 'warning';
    /** @var string **/
    const NOTICE    = 'notice';
    /** @var string **/
    const INFO      = 'info';
    /** @var string **/
    const DEBUG     = 'debug';

    /**
     * @inheritDoc
     */
    abstract public function emergency($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function alert($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function critical($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function error($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function warning($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function notice($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function info($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function debug($message, array $context = array());

    /**
     * @inheritDoc
     */
    abstract public function log($level, $message, array $context = array());

    /**
     * Adds the current date to the message.
     * @param  string $message
     * @return string
     */
    protected function withDate($message)
    {
        return sprintf(
            '%s %s',
            $message,
            (new DateTime('now'))->format('d/m/Y H:i')
        );
    }

}
