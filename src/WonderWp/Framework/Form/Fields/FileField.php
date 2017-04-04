<?php

namespace WonderWp\Framework\Form\Fields;

class FileField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type = 'file';
    }
}
