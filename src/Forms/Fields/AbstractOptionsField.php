<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 31/08/2016
 * Time: 12:27
 */

namespace WonderWp\Forms\Fields;

trait AbstractOptionsField {

    protected $options = array();

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

}
