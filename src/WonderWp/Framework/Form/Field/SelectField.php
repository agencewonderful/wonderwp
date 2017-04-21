<?php

namespace WonderWp\Framework\Form\Field;

class SelectField extends AbstractField implements OptionsFieldInterface
{
    use OptionsFieldTrait;

    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->tag = 'select';
    }
}
