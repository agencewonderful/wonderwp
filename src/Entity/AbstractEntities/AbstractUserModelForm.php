<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/11/2016
 * Time: 18:10
 */

namespace WonderWp\Entity\AbstractEntities;


use WonderWp\Entity\EntityAttribute;
use WonderWp\Forms\Fields\EmailField;
use WonderWp\Forms\Fields\FieldGroup;
use WonderWp\Forms\Fields\NumericField;
use WonderWp\Forms\ModelForm;

class AbstractUserModelForm extends ModelForm
{

    public function newField(EntityAttribute $attr)
    {
        $fieldName = $attr->getFieldName();
        $entity = $this->getModelInstance();
        $val = $entity->$fieldName;
        $label = __($fieldName . '.trad', $this->_textDomain);

        //Add here particular cases for your different fields
        switch($fieldName){

            case 'dob':
                $f = new FieldGroup($fieldName,$val,['label' => $label,'inputAttributes'=>['class'=>['form-inline']]]);

                $d = new NumericField($fieldName.'_day',null,[
                    'label'=>__('day',$this->_textDomain),
                    'inputAttributes'=>[
                        'name'=>'dob[day]',
                        'maxlength'=>2,
                        'placeholder'=>__('jj',$this->_textDomain)
                    ]
                ]);
                $f->addFieldToGroup($d);

                $m = new NumericField($fieldName.'_month',null,[
                    'label'=>__('month',$this->_textDomain),
                    'inputAttributes'=>[
                        'name'=>'dob[month]',
                        'maxlength'=>2,
                        'placeholder'=>__('mm',$this->_textDomain)
                    ]
                ]);
                $f->addFieldToGroup($m);

                $y = new NumericField($fieldName.'_year',null,[
                    'label'=>__('year',$this->_textDomain),
                    'inputAttributes'=>[
                        'name'=>'dob[year]',
                        'maxlength'=>4,
                        'placeholder'=>__('aaaa',$this->_textDomain)
                    ]
                ]);
                $f->addFieldToGroup($y);
                break;

            case 'email':
                $f = new EmailField($fieldName,$val,['label' => $label]);
                break;

            default:
                $f = parent::newField($attr);
                break;
        }
        return $f;
    }

}
