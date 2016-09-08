<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 31/08/2016
 * Time: 14:07
 */

namespace WonderWp\Forms\Fields;

use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Fields\AbstractOptionsField;

class FieldGroup extends AbstractField{

    //Options trait
    use AbstractOptionsField;

    protected $group=array();

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array())
    {
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->tag = 'div';
        if(empty($this->displayRules['inputAttributes']['class'])){ $this->displayRules['inputAttributes']['class']=array(); }
        $this->displayRules['inputAttributes']['class'][] = 'field-group';
    }

    public function addFieldToGroup(AbstractField $field){
        $fieldName = $field->getName();
        $this->group[$fieldName] = $field;
    }

    /**
     * @return array
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param array $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

}