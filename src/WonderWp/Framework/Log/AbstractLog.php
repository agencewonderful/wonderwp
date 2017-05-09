<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 04/05/2017
 * Time: 11:03
 */

namespace WonderWp\Framework\Log;

use WonderWp\Framework\API\Result;

abstract class AbstractLog implements LogInterface
{
    /**
     * @var array
     */
    protected $log = [];

    const SUCCESS_TYPE = 'success';
    const ERROR_TYPE   = 'error';

    protected $startTime;

    /**
     * @inheritdoc
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @inheritdoc
     */
    public function setLog(array $log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addEntry($toLog)
    {
        $this->log[] = [$toLog, '', $this->getExecTime()];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addSuccess($toLog)
    {
        $this->log[] = [$toLog, self::SUCCESS_TYPE, $this->getExecTime()];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addError($toLog)
    {
        $this->log[] = [$toLog, self::ERROR_TYPE, $this->getExecTime()];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function display()
    {
        \WonderWp\Framework\trace($this->log);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return new Result(200);
    }

    protected function getExecTime()
    {
        if (empty($this->startTime)) {
            $this->startTime = microtime(true);
            $execTime        = microtime(true);
        } else {
            $timestamp = microtime(true);
            $execTime  = $timestamp - $this->startTime;
        }

        return $execTime;
    }

}
