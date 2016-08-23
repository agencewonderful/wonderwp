<?php

namespace WonderWp\Forms;

use WonderWp\Forms\Fields\fieldInterface;

interface FormInterface {

    /**
     * @return mixed
     */
    public function getFields();

    /**
     * @param mixed $fields
     */
    public function setFields($fields);

    /**
     * @return mixed
     */
    public function getErrors();

    /**
     * @param mixed $errors
     */
    public function setErrors($errors);

    public function addField(fieldInterface $field);

    public function getField($fieldName);

}