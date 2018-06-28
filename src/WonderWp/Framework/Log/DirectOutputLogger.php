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
    public function log($level, $message, array $context = [])
    {
        echo($this->withTime('[' . strtoupper($level) . ']') . ' ' . $message . PHP_EOL);
    }

}
