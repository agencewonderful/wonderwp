<?php

namespace WonderWp\Framework\Form\Field;

class FieldGroup extends AbstractField implements FieldGroupInterface
{
    //use OptionsFieldTrait;

    /** @var FieldInterface[] */
    protected $group = [];

    /** @inheritdoc */
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->tag = 'div';

        if (!array_key_exists('class', $this->displayRules['inputAttributes'])) {
            $this->displayRules['inputAttributes']['class'] = [];
        }

        $this->displayRules['inputAttributes']['class'][] = 'field-group';
    }

    /** @inheritdoc */
    public function addFieldToGroup(FieldInterface $field)
    {
        $fieldName               = $field->getName();
        $this->group[$fieldName] = $field;

        return $this;
    }

    /** @inheritdoc */
    public function getGroup()
    {
        return $this->group;
    }

    /** @inheritdoc */
    public function setGroup(array $group)
    {
        $this->group = $group;

        return $this;
    }
}
