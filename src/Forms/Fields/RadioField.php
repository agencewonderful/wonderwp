<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/09/2016
 * Time: 17:49
 */

namespace WonderWp\Forms\Fields;


use WonderWp\DI\Container;
use WonderWp\Forms\Validation\Validator;

class RadioField extends FieldGroup
{
    public function __construct($name, $value = null, $displayRules = array(), $validationRules = array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        if(empty($this->displayRules['wrapAttributes']['class'])){ $this->displayRules['wrapAttributes']['class']=array(); }
        $this->displayRules['wrapAttributes']['class'][] = 'radio-group';
    }

    public function generateRadios($passedGroupedDisplayRules=array(),$passedGroupedValidationRules=array())
    {
        $name = $this->getName();
        if (!empty($this->options)) {
            $i=0;
            foreach ($this->options as $val => $label) {
                $optFieldName = $name . '.' . $val . '';
                $defaultOptDisplayRules = array(
                    'label' => $label,
                    'inputAttributes' => array(
                        'name' => $name,
                        'id'=> $name. '.' . $val,
                        'value' => $val
                    ),
                    'wrapAttributes'=>array(
                        'class' => ['radio-wrap']
                    )
                );
                if($val==$this->value){
                    $defaultOptDisplayRules['inputAttributes']['checked']='';
                }
                $passedOptDisplayRules = isset($passedGroupedDisplayRules[$val]) ? $passedGroupedDisplayRules[$val] : array();
                $optDisplayRules =\WonderWp\array_merge_recursive_distinct($defaultOptDisplayRules,$passedOptDisplayRules);

                $validationRules = [];
                if($i===0) {
                    $formValidator = Container::getInstance()->offsetGet('wwp.forms.formValidator');
                    if ($formValidator::hasRule($this->getValidationRules(), 'NotEmpty')) {
                        $validationRules[] = Validator::notEmpty();
                    }
                }

                $optField = new InputField($optFieldName, isset($this->value[$val]) ? $this->value[$val] : null, $optDisplayRules,$validationRules);
                $optField->setType('radio');
                $this->addFieldToGroup($optField);
            }
        }
        return $this;
    }

    public function setValue($value)
    {
        parent::setValue($value);

        if(!empty($this->group)){
            foreach ($this->group as $cbField){
                /** @var CheckBoxField $cbField */
                $displayRules = $cbField->getDisplayRules();
                $cbValue = !empty($displayRules['inputAttributes']['value']) ? $displayRules['inputAttributes']['value'] : null;

                if(!empty($cbValue) && isset($this->value[$cbValue])){
                    $cbField->setValue($this->value[$cbValue]);
                }

                if ($cbValue == $value) {
                    $displayRules['inputAttributes']['checked'] = '';
                    $cbField->setDisplayRules($displayRules);
                } elseif (array_key_exists('checked', $displayRules['inputAttributes'])) {
                    unset($displayRules['inputAttributes']['checked']);
                    $cbField->setDisplayRules($displayRules);
                }
            }
        }
        return $this;
    }
}
