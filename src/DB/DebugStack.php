<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 07/12/2016
 * Time: 11:52
 */
namespace WonderWp\DB;

class DebugStack extends \Doctrine\DBAL\Logging\DebugStack{
    public $timeSpent = 0;

    public function stopQuery()
    {
        parent::stopQuery();
        if ($this->enabled) {
            $this->timeSpent += $this->queries[$this->currentQuery]['executionMS'];
        }
    }

}