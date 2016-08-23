<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/08/2016
 * Time: 11:16
 */

namespace WonderWp\Forms\Fields;

class InputField extends AbstractField{

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->tag = 'input';
        $this->type = 'text';

        return $this;
    }

}