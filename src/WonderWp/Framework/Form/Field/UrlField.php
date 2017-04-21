<?php

namespace WonderWp\Framework\Form\Field;

class UrlField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type = 'url';

        if (!array_key_exists('class', $this->displayRules['inputAttributes'])) {
            $this->displayRules['inputAttributes']['class'] = [];
        }

        $this->displayRules['inputAttributes']['class'][] = 'text';
    }
}
