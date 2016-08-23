<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/08/2016
 * Time: 17:11
 */
namespace WonderWp\Forms\Fields;

class HiddenField extends InputField{

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array()){
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->type = 'hidden';
        $this->label = null;
    }

}