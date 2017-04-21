<?php

namespace WonderWp\Framework\Form\Field;

class CheckBoxField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type = 'checkbox';

        if (!array_key_exists('value', $this->displayRules['inputAttributes'])) {
            $this->displayRules['inputAttributes']['value'] = 1;
        }

        if (!array_key_exists('class', $this->displayRules['wrapAttributes'])) {
            $this->displayRules['wrapAttributes']['class'] = [];
        }

        $this->displayRules['wrapAttributes']['class'][] = 'checkbox-wrap';
    }
}
