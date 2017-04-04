<?php

namespace WonderWp\Framework\Form;

use WonderWp\Framework\Form\Fields\FieldInterface;
use WonderWp\Framework\Form\Fields\HiddenField;

class FormViewReadOnly extends FormView
{
    /** @inheritdoc */
    public function formStart(array $optsStart = [])
    {
        $defaultOptions = [
            'class' => ['wwpReadonlyForm'],
        ];
        $options        = array_merge_recursive($defaultOptions, $optsStart);

        return parent::formStart($options);
    }

    /** @inheritdoc */
    public function renderField($fieldName)
    {
        $markup = '';
        $f      = ($fieldName instanceof FieldInterface) ? $fieldName : $this->_formInstance->getField($fieldName);

        if ($f->isRendered()) {
            return $markup;
        }

        $markup .= $this->fieldWrapStart($fieldName);

        $markup .= $this->fieldLabel($fieldName);

        $markup .= $this->fieldStart($fieldName);
        $markup .= $this->fieldBetween($fieldName);
        $markup .= $this->fieldEnd($fieldName);

        $markup .= $this->fieldWrapEnd($fieldName);

        $f->setRendered(true);

        return $markup;
    }

    /** @inheritdoc */
    public function fieldStart($fieldName)
    {
        return '<span class="readOnlyVal">';
    }

    /** @inheritdoc */
    public function fieldBetween($fieldName)
    {
        $markup = '';
        /** @var FieldInterface $f */
        $f = ($fieldName instanceof FieldInterface) ? $fieldName : $this->_formInstance->getField($fieldName);

        if (!empty($f)) {
            $val = $f->getValue();
            if ($val instanceof \DateTime) {
                $val = $val->format('d/m/Y');
            }

            if ($f instanceof HiddenField) {
                return '';
            }

            if (method_exists($f, 'getOptions') && !empty($f->getOptions())) {
                $opts = $f->getOptions();
                if (!empty($opts[$val])) {
                    $markup .= $opts[$val];
                }
            } else {
                $markup .= print_r($val, true);
            }
        }

        return $markup;
    }

    /** @inheritdoc */
    public function fieldEnd($fieldName)
    {
        return '</span>';
    }
}
