<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 04/05/2017
 * Time: 11:02
 */

namespace WonderWp\Framework\Log;

class DirectOutputLog extends AbstractLog
{
    /**
     * @inheritDoc
     */
    public function addEntry($toLog)
    {
        parent::addEntry($toLog);
        dump(end($this->log));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addSuccess($toLog)
    {
        parent::addSuccess($toLog);
        dump(end($this->log));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addError($toLog)
    {
        parent::addError($toLog);
        dump(end($this->log));
        return $this;
    }

}
