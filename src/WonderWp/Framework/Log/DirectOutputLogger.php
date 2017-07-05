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
        print_r('[EMERGENCY]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = array())
    {
        print_r('[ALERT]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = array())
    {
        print_r('[CRITICAL]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = array())
    {
        print_r('[ERROR]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = array())
    {
        print_r('[WARNING]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = array())
    {
        print_r('[NOTICE]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = array())
    {
        print_r('[INFO]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = array())
    {
        print_r('[DEBUG]' . $this->withDate($message));
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        print_r($this->withDate($message));
    }

}
