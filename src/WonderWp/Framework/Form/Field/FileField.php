<?php

namespace WonderWp\Framework\Form\Field;

class FileField extends InputField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->type = 'file';
    }
}
