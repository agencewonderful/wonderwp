<?php

namespace WonderWp\Framework\Form\Field;

interface OptionsFieldInterface
{
    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     *
     * @return static
     */
    public function setOptions(array $options);
}
