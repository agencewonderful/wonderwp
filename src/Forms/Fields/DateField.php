<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/09/2016
 * Time: 16:21
 */

namespace WonderWp\Forms\Fields;


class DateField extends InputField
{
    /**
     * NumericField constructor.
     * @param $name
     * @param null $value
     * @param array $displayRules
     * @param array $validationRules
     * @return DateField
     */
    public function __construct($name, $value, $displayRules=array(), $validationRules=array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->type = 'date';

        if(empty($this->displayRules['inputAttributes']['class'])){ $this->displayRules['inputAttributes']['class']=array(); }
        $this->displayRules['inputAttributes']['class'][] = 'text';

        return $this;
    }
}