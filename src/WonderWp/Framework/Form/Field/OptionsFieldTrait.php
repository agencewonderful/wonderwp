<?php

namespace WonderWp\Framework\Form\Field;

trait OptionsFieldTrait
{
    protected $options = [];

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return static
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param string|int $key
     * @param string|int $val
     *
     * @return $this
     */
    public function addOption($key, $val)
    {
        $this->options[$key] = $val;

        return $this;
    }
}
