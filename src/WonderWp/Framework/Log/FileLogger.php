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
    public function log($level, $message, array $context = array())
    {
        $file = fopen($this->path, 'a');

        fwrite(
            $file,
            $this->withDate('[' . strtoupper($level) . ']') . PHP_EOL . $message . PHP_EOL
        );

        fclose($file);
    }

}
