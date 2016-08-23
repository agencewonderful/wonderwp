<?php

namespace WonderWp\Forms\Fields;

class TextAreaField extends AbstractField{

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->tag = 'textarea';

        if(empty($this->displayRules['inputAttributes']['class'])){ $this->displayRules['inputAttributes']['class']=array(); }
        $this->displayRules['inputAttributes']['class'][] = 'text';
    }

}