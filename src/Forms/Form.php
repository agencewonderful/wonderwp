<?php

namespace WonderWp\Forms;

use WonderWp\DI\Container;
use WonderWp\Forms\Fields\fieldInterface;

class Form implements FormInterface{

    protected $_fields;

    protected $_errors;

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        $this->_errors = $errors;
    }

    public function addField(fieldInterface $field){
        $this->_fields[$field->getName()] = $field;
    }

    public function getField($fieldName){
        return !empty($this->_fields[$fieldName]) ? $this->_fields[$fieldName] : null;
    }

    public function renderView($opts=array()){
        $container = Container::getInstance();
        /** @var FormView $formView */
        $formView = $container->offsetGet('wwp.forms.formView');
        $formView->setFormInstance($this);
        return $formView->render($opts);
    }

}