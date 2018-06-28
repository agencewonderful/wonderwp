<?php

namespace WonderWp\Framework\Form\Field;

class DateField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value, array $displayRules = [], array $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type = 'date';

        if (!array_key_exists('class', $this->displayRules['inputAttributes'])) {
            $this->displayRules['inputAttributes']['class'] = [];
        }

        $this->displayRules['inputAttributes']['class'][] = 'text';
    }
}
