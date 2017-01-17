<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/11/2016
 * Time: 18:10
 */

namespace WonderWp\Entity\AbstractEntities;


use WonderWp\Entity\EntityAttribute;
use WonderWp\Forms\Fields\BooleanField;
use WonderWp\Forms\Fields\CategoryField;
use WonderWp\Forms\Fields\DateField;
use WonderWp\Forms\Fields\EmailField;
use WonderWp\Forms\Fields\FieldGroup;
use WonderWp\Forms\Fields\NumericField;
use WonderWp\Forms\Fields\PasswordField;
use WonderWp\Forms\Fields\RadioField;
use WonderWp\Forms\Fields\SelectField;
use WonderWp\Forms\ModelForm;
use WonderWp\Plugin\Forms\Fields\MediaField;

class AbstractPostrelativeModelForm extends ModelForm
{

    public function newField(EntityAttribute $attr)
    {
        $fieldName = $attr->getFieldName();
        $entity = $this->getModelInstance();
        $val = $entity->$fieldName;
        $label = __($fieldName . '.trad', $this->_textDomain);

        //Add here particular cases for your different fields
        switch($fieldName){

            case 'visual':
                $f = new MediaField($fieldName,$val);
                break;

            default:
                $f = parent::newField($attr);
                break;
        }
        return $f;
    }

}