<?php

namespace WonderWp\Framework\Form\Field;

class HiddenField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [])
    {
        if (array_key_exists('label', $displayRules)) {
            unset($displayRules['label']);
        }

        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type                                      = 'hidden';
        $this->displayRules['wrapAttributes']['no-wrap'] = true;
    }
}
