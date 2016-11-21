<?php

namespace WonderWp\Forms\Fields;

use WonderWp\Forms\Fields\FieldLabel;

abstract class AbstractField implements FieldInterface{

    /**
     * Field Name
     * @var string
     */
    protected $name;

    protected $tag;
    protected $type;

    protected $value;

    protected $errors = array();

    protected $displayRules = array();

    protected $validationRules = array();

    protected $rendered = false;

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array())
    {
        $this
            ->setName($name)
            ->setValue($value);

        $this->computeDisplayRules($displayRules);
        $this->computeValidationRules($validationRules);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getDisplayRules()
    {
        return $this->displayRules;
    }

    /**
     * @param array $displayRules
     */
    public function setDisplayRules($displayRules)
    {
        $this->displayRules = $displayRules;
        return $this;
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    /**
     * @param array $validationRules
     */
    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return boolean
     */
    public function getRendered()
    {
        return $this->rendered;
    }

    /**
     * @param boolean $rendered
     * @return AbstractField
     */
    public function setRendered($rendered)
    {
        $this->rendered = $rendered;
        return $this;
    }

    public function computeDisplayRules($passedRules){
        $defaultRules = array(
            'wrapAttributes'=>array(),
            'inputAttributes'=>array(
                'name' => $this->name,
                'id' => $this->name
            ),
            'labelAttributes'=>array(
                'for'=>$this->name
            )
        );

        $this->displayRules = \WonderWp\array_merge_recursive_distinct($this->displayRules, $defaultRules);
        $this->displayRules = \WonderWp\array_merge_recursive_distinct($this->displayRules,$passedRules);
        return $this;
    }

    public function computeValidationRules($passedRules){
        $this->validationRules = array_merge($this->validationRules,$passedRules);
        return $this;
    }
}
