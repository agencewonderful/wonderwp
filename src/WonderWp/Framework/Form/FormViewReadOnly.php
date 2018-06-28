<?php

namespace WonderWp\Framework\Form;

use function WonderWp\Framework\array_merge_recursive_distinct;
use WonderWp\Framework\Form\Field\FieldGroupInterface;
use WonderWp\Framework\Form\Field\HiddenField;
use WonderWp\Framework\Form\Field\OptionsFieldInterface;

class FormViewReadOnly extends FormView
{
    /** @inheritdoc */
    public function formStart(array $optsStart = [])
    {
        $defaultOptions = [
            'class' => ['wwpReadonlyForm'],
        ];
        $options        = array_merge_recursive_distinct($defaultOptions, $optsStart);

        return parent::formStart($options);
    }

    /** @inheritdoc */
    public function renderField($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if ($field === null) {
            return '';
        }

        if ($field->isRendered()) {
            return '';
        }

        $markup = $this->fieldWrapStart($field);
        $markup .= $this->fieldLabel($field);
        $markup .= $this->fieldStart($field);
        $markup .= $this->fieldBetween($field);
        $markup .= $this->fieldEnd($field);
        $markup .= $this->fieldWrapEnd($field);

        $field->setRendered(true);

        return $markup;
    }

    /** @inheritdoc */
    public function fieldStart($field)
    {
        return '<span class="readOnlyVal">';
    }

    /** @inheritdoc */
    public function fieldBetween($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if ($field === null) {
            return '';
        }

        $markup = '';

        $val = $field->getValue();

        //If group -> recurse
        if ($field instanceof FieldGroupInterface && !$field instanceof OptionsFieldInterface) {
            $group = $field->getGroup();

            foreach ($group as $fFromFroup) {
                $markup .= $this->renderField($fFromFroup);
            }
        }

        if ($val instanceof \DateTime) {
            $val = $val->format('d/m/Y');
        }

        if ($field instanceof HiddenField) {
            return '';
        }

        if ($field instanceof OptionsFieldInterface) {
            $opts = $field->getOptions();

            if (array_key_exists($val, $opts) && !empty($opts[$val])) {
                $markup .= $opts[$val];
            }
        } else {
            $markup .= print_r($val, true);
        }

        return $markup;
    }

    /** @inheritdoc */
    public function fieldEnd($field)
    {
        return '</span>';
    }

    public function formEnd(array $optsEnd = [])
    {
        if (!isset($optsEnd['showSubmit'])) {
            $optsEnd['showSubmit'] = false;
        }

        return parent::formEnd($optsEnd);
    }
}
