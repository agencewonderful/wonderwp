<?php

namespace WonderWp\Framework\Form\Field;

interface FieldInterface
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
     * @return string
     */
    public function getTag();

    /**
     * @param string $tag
     *
     * @return static
     */
    public function setTag($tag);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     *
     * @return static
     */
    public function setType($type);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function setValue($value);

    /**
     * @return array
     */
    public function getDisplayRules();

    /**
     * @param array $displayRules
     *
     * @return static
     */
    public function setDisplayRules($displayRules);

    /**
     * @return array
     */
    public function getValidationRules();

    /**
     * @param array $validationRules
     *
     * @return static
     */
    public function setValidationRules($validationRules);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @param array $errors
     *
     * @return static
     */
    public function setErrors($errors);

    /**
     * @return bool
     */
    public function isRendered();

    /**
     * @param bool $rendered
     *
     * @return static
     */
    public function setRendered($rendered);
}
