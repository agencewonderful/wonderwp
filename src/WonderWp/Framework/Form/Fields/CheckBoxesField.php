<?php

namespace WonderWp\Framework\Form\Fields;

class CheckBoxesField extends FieldGroup
{
    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        if (!array_key_exists('class', $this->displayRules['wrapAttributes'])) {
            $this->displayRules['wrapAttributes']['class'] = [];
        }

        $this->displayRules['wrapAttributes']['class'][] = 'checkbox-group';
    }

    /**
     * @param array $passedGroupedDisplayRules
     * @param array $passedGroupedValidationRules
     *
     * @return static
     */
    public function generateCheckBoxes($passedGroupedDisplayRules = [], $passedGroupedValidationRules = [])
    {
        $name = $this->getName();

        foreach ($this->options as $val => $label) {
            $optFieldName           = $name . '.' . $val . '';
            $defaultOptDisplayRules = [
                'label'           => $label,
                'inputAttributes' => [
                    'name'  => $name . '[' . $val . ']',
                    'value' => $val,
                ],
            ];
            $passedOptDisplayRules  = isset($passedGroupedDisplayRules[$val]) ? $passedGroupedDisplayRules[$val] : [];
            $optDisplayRules        = \WonderWp\Framework\array_merge_recursive_distinct($defaultOptDisplayRules, $passedOptDisplayRules);

            $optField = new CheckBoxField($optFieldName, isset($this->value[$val]) ? $this->value[$val] : null, $optDisplayRules);
            $this->addFieldToGroup($optField);
        }

        return $this;
    }

    /** @inheritdoc */
    public function setValue($value)
    {
        parent::setValue($value);

        if (!empty($this->group)) {
            foreach ($this->group as $cbField) {
                /** @var CheckBoxField $cbField */
                $displayRules = $cbField->getDisplayRules();
                $value        = !empty($displayRules['inputAttributes']['value']) ? $displayRules['inputAttributes']['value'] : null;
                if (!empty($value) && isset($this->value[$value])) {
                    $cbField->setValue($this->value[$value]);
                }
            }
        }

        return $this;
    }

}
