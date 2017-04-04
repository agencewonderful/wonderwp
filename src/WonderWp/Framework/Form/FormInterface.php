<?php

namespace WonderWp\Framework\Form;

use WonderWp\Framework\Form\Field\FieldInterface;

interface FormInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName($name);

    /**
     * @return FieldInterface[]
     */
    public function getFields();

    /**
     * @param string $name
     *
     * @return FieldInterface
     */
    public function getField($name);

    /**
     * @param FieldInterface $field
     *
     * @return static
     */
    public function addField(FieldInterface $field = null);

    /**
     * @param array FieldInterface[] $fields
     */
    public function setFields(array $fields);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @param array $errors
     */
    public function setErrors($errors);

    /**
     * @return FormGroup[]
     */
    public function getGroups();

    /**
     * @param string $name
     *
     * @return FormGroup
     */
    public function getGroup($name);

    /**
     * @param FormGroup[] $groups
     *
     * @return static
     */
    public function setGroups(array $groups);

    /**
     * @param FormGroup $group
     *
     * @return static
     */
    public function addGroup(FormGroup $group);

    /**
     * @param string $name
     *
     * @return static
     */
    public function removeGroup($name);

    /**
     * @param array $data
     *
     * @return static
     */
    public function fill(array $data);
}
