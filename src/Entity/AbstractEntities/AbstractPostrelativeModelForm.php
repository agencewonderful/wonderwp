<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/11/2016
 * Time: 18:10
 */

namespace WonderWp\Entity\AbstractEntities;


use WonderWp\Entity\EntityAttribute;
use WonderWp\Forms\ModelForm;
use WonderWp\Plugin\Forms\Fields\MediaField;

class AbstractPostrelativeModelForm extends ModelForm
{

    public function newField(EntityAttribute $attr)
    {
        $fieldName = $attr->getFieldName();
        $entity = $this->getModelInstance();
        $val = $entity->$fieldName;

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