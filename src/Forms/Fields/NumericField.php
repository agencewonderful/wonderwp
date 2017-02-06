<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 06/09/2016
 * Time: 11:08
 */

namespace WonderWp\Forms\Fields;

class NumericField extends InputField{

    /**
     * NumericField constructor.
     * @param $name
     * @param null $value
     * @param array $displayRules
     * @param array $validationRules
     * @return NumericField
     */
    public function __construct($name, $value, $displayRules=array(), $validationRules=array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->type = 'number';

        if(empty($this->displayRules['inputAttributes']['class'])){ $this->displayRules['inputAttributes']['class']=array(); }
        $this->displayRules['inputAttributes']['class'][] = 'text';

        return $this;
    }

}
