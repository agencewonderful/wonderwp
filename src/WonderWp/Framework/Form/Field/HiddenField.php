<?php

namespace WonderWp\Framework\Form\Field;

class HiddenField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [])
    {
        $displayRules['label'] = false;

        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type                                      = 'hidden';
        $this->displayRules['wrapAttributes']['no-wrap'] = true;
    }
}
