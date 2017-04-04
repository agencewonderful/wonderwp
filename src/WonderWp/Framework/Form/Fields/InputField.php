<?php

namespace WonderWp\Framework\Form\Fields;

class InputField extends AbstractField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->tag  = 'input';
        $this->type = 'text';
    }
}
