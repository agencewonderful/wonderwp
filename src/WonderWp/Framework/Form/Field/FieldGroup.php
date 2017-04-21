<?php

namespace WonderWp\Framework\Form\Field;

class FieldGroup extends AbstractField
{
    use AbstractOptionsField;

    /** @var array */
    protected $group = [];

    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->tag = 'div';

        if (!array_key_exists('class', $this->displayRules['inputAttributes'])) {
            $this->displayRules['inputAttributes']['class'] = [];
        }

        $this->displayRules['inputAttributes']['class'][] = 'field-group';
    }

    /**
     * @param AbstractField $field
     *
     * @return static
     */
    public function addFieldToGroup(AbstractField $field)
    {
        $fieldName               = $field->getName();
        $this->group[$fieldName] = $field;

        return $this;
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
