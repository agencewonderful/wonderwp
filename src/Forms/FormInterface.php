<?php

namespace WonderWp\Forms;

use WonderWp\Forms\Fields\fieldInterface;

/**
 * Interface FormInterface
 * @package WonderWp\Forms
 */
interface FormInterface
{
    /**
     * @param $name
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function setName($name);

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

    /**
     * @param fieldInterface $field
     * @return mixed
     */
    public function addField(fieldInterface $field = null);

    /**
     * @param $fieldName
     * @return mixed
     */
    public function getField($fieldName);

    /**
     * @return mixed
     */
    public function getGroups();

    /**
     * @param $groups
     * @return mixed
     */
    public function setGroups($groups);

    /**
     * @param FormGroup $group
     * @return mixed
     */
    public function addGroup(FormGroup $group);

    public function fill(array $data);

}