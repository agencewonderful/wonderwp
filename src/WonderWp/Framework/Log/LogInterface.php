<?php

namespace WonderWp\Framework\Log;

use WonderWp\Framework\API\Result;

interface LogInterface
{

    /**
     * @return array
     */
    public function getLog();

    /**
     * @param array $log
     *
     * @return static
     */
    public function setLog(array $log);

    /**
     * Add a log entry
     *
     * @param mixed $toLog
     *
     * @return static
     */
    public function addEntry($toLog);

    /**
     * @param $toLog
     *
     * @return static
     */
    public function addSuccess($toLog);

    /**
     * @param $toLog
     *
     * @return static
     */
    public function addError($toLog);

    /**
     * Display a formatted version of the log
     *
     * @return static
     */
    public function display();

    /**
     * @return Result
     */
    public function save();
}
