<?php

namespace WonderWp\Forms\Fields;

interface FieldInterface{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getTag();

    /**
     * @param mixed $tag
     */
    public function setTag($tag);

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @param mixed $type
     */
    public function setType($type);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $type
     */
    public function setValue($type);

    /**
     * @return array
     */
    public function getDisplayRules();

    /**
     * @param array $displayRules
     */
    public function setDisplayRules($displayRules);

    /**
     * @return array
     */
    public function getValidationRules();

    /**
     * @param array $validationRules
     */
    public function setValidationRules($validationRules);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @param array $validationRules
     */
    public function setErrors($validationRules);

}
