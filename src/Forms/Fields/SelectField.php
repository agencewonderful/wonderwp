<?php

namespace WonderWp\Forms\Fields;

class SelectField extends AbstractField{

    use AbstractOptionsField;

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->tag = 'select';
    }

}