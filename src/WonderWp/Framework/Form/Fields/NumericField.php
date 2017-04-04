<?php

namespace WonderWp\Framework\Form\Fields;

class NumericField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type = 'number';

        if (!array_key_exists('class', $this->displayRules['inputAttributes'])) {
            $this->displayRules['inputAttributes']['class'] = [];
        }

        $this->displayRules['inputAttributes']['class'][] = 'text';
    }

}
