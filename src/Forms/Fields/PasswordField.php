<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/09/2016
 * Time: 12:26
 */

namespace WonderWp\Forms\Fields;


class PasswordField extends InputField
{
    public function __construct($name, $value, $displayRules = array(), $validationRules = array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->type = 'password';

        if(empty($this->displayRules['inputAttributes']['class'])){ $this->displayRules['inputAttributes']['class']=array(); }
        $this->displayRules['inputAttributes']['class'][] = 'text';

        return $this;
    }
}