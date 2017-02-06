<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/08/2016
 * Time: 11:16
 */

namespace WonderWp\Forms\Fields;

class CheckBoxField extends InputField{

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->type = 'checkbox';

        if(empty($this->displayRules['inputAttributes']['value'])){ $this->displayRules['inputAttributes']['value']=1; }
        if(empty($this->displayRules['wrapAttributes']['class'])){ $this->displayRules['wrapAttributes']['class']=array(); }
        $this->displayRules['wrapAttributes']['class'][] = 'checkbox-wrap';
    }

}
