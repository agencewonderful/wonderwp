<?php

namespace WonderWp\Framework\Form\Field;

use function WonderWp\Framework\array_merge_recursive_distinct;

abstract class AbstractField implements FieldInterface
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $tag;
    /** @var string */
    protected $type;
    /** @var mixed */
    protected $value;
    /** @var array */
    protected $errors = [];
    /** @var array */
    protected $displayRules = [];
    /** @var array */
    protected $validationRules = [];
    /** @var bool */
    protected $rendered = false;

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $displayRules
     * @param array  $validationRules
     */
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [])
    {
        $this
            ->setName($name)
            ->setValue($value)
            ->computeDisplayRules($displayRules)
            ->computeValidationRules($validationRules)
        ;
    }

    /** @inheritdoc */
    public function getName()
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /** @inheritdoc */
    public function getTag()
    {
        return $this->tag;
    }

    /** @inheritdoc */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /** @inheritdoc */
    public function getType()
    {
        return $this->type;
    }

    /** @inheritdoc */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /** @inheritdoc */
    public function getValue()
    {
        return $this->value;
    }

    /** @inheritdoc */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /** @inheritdoc */
    public function getDisplayRules()
    {
        return $this->displayRules;
    }

    /** @inheritdoc */
    public function setDisplayRules(array $displayRules)
    {
        $this->displayRules = $displayRules;

        return $this;
    }

    /** @inheritdoc */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    /** @inheritdoc */
    public function setValidationRules(array $validationRules)
    {
        $this->validationRules = $validationRules;

        return $this;
    }

    /** @inheritdoc */
    public function getErrors()
    {
        return $this->errors;
    }

    /** @inheritdoc */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /** @inheritdoc */
    public function isRendered()
    {
        return $this->rendered;
    }

    /** @inheritdoc */
    public function setRendered($rendered)
    {
        $this->rendered = $rendered;

        return $this;
    }

    /** @inheritdoc */
    public function computeDisplayRules(array $passedRules)
    {
        $defaultRules = [
            'wrapAttributes'  => [],
            'inputAttributes' => [
                'name' => $this->name,
                'id'   => $this->name,
            ],
            'labelAttributes' => [
                'for' => $this->name,
            ],
        ];

        $this->displayRules = array_merge_recursive_distinct($this->displayRules, $defaultRules);
        $this->displayRules = array_merge_recursive_distinct($this->displayRules, $passedRules);

        return $this;
    }

    /** @inheritdoc */
    public function computeValidationRules(array $passedRules)
    {
        $this->validationRules = array_merge($this->validationRules, $passedRules);

        return $this;
    }
}

