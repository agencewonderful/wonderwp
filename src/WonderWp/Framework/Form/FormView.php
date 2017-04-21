<?php

namespace WonderWp\Framework\Form;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Form\Field\FieldInterface;
use WonderWp\Framework\Form\Field\SelectField;

class FormView implements FormViewInterface
{
    /** @var FormInterface */
    protected $formInstance;
    /** @var Container */
    protected $container;

    /** Constructor */
    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /** @inheritdoc */
    public function setFormInstance(FormInterface $form)
    {
        $this->formInstance = $form;

        return $this;
    }

    /** @inheritdoc */
    public function getFormInstance()
    {
        return $this->formInstance;
    }

    /** @inheritdoc */
    public function render(array $opts = [])
    {
        $markup    = '';
        $optsStart = !empty($opts['formStart']) ? $opts['formStart'] : [];
        $optsEnd   = !empty($opts['formEnd']) ? $opts['formEnd'] : [];
        $markup    .= $this->formStart($optsStart);
        $markup    .= $this->formErrors();
        $fields    = $this->getFormInstance()->getFields();

        $allowedFields = array_keys($fields);
        if (!empty($opts['allowFields'])) {
            $allowedFields = $opts['allowFields'];
        }
        if (!empty($opts['excludeFields'])) {
            $allowedFields = array_diff($allowedFields, $opts['excludeFields']);
        }

        if (!empty($fields)) {
            foreach ($fields as $i => $f) {
                if (in_array($i, $allowedFields)) {
                    /* @var $f FieldInterface */
                    $markup .= $this->renderField($f->getName());
                }
            }
        }
        $groups = $this->getFormInstance()->getGroups();
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $markup .= $this->renderGroup($group);
            }
        }
        $markup .= $this->formEnd($optsEnd);

        return $markup;
    }

    /** @inheritdoc */
    public function formStart(array $optsStart = [])
    {
        $defaultOptions = [
            'showFormTag' => 1,
            'method'      => 'post',
            'enctype'     => 'multipart/form-data',
            'class'       => ['wwpform', $this->formInstance->getName()],
        ];
        $options        = array_merge_recursive($defaultOptions, $optsStart);
        $markup         = '';
        if ($options['showFormTag'] == 1) {
            if (!empty($options['showFormTag'])) {
                unset($options['showFormTag']);
            }
            $markup .= '<form ' . \WonderWp\Framework\paramsToHtml($options) . '>';
        }

        return $markup;
    }

    /**
     * @return string
     */
    public function formErrors()
    {
        $markup = '';

        $errors = $this->formInstance->getErrors();
        if (!empty($errors)) {
            $markup .= '<div class="form-errors"></div>';
        }

        return $markup;
    }

    /**
     * @param FormGroup $group
     *
     * @return string
     */
    public function renderGroup(FormGroup $group)
    {
        $markup = '';
        $fields = $group->getFields();
        if (!empty($fields)) {
            $markup .= '<fieldset ' . \WonderWp\Framework\paramsToHtml($group->getDisplayRules()) . '>
            <legend class="hndle ui-sortable-handle">' . $group->getTitle() . '</legend>            
            <div class="inside">';
            foreach ($fields as $field) {
                $markup .= $this->renderField($field);
            }
            $markup .= '</div></fieldset>';
        }

        return $markup;
    }

    /** @inheritdoc */
    public function renderField($fieldName)
    {
        $markup = '';
        $f      = $fieldName instanceof FieldInterface ? $fieldName : $this->formInstance->getField($fieldName);
        if(!$f instanceof FieldInterface && is_string($fieldName)){
            if(WP_ENV==='development') {
                throw new \UnexpectedValueException($fieldName . ' is not an instance of FieldInterface');
            } else {
                return '';
            }
        }

        if ($f->isRendered()) {
            return $markup;
        }

        $type = (is_object($f)) ? $f->getType() : null;

        $markup .= $this->fieldWrapStart($fieldName);
        if ($type !== 'radio' && $type !== 'checkbox') {
            $markup .= $this->fieldLabel($fieldName);
        }
        $markup .= $this->fieldStart($fieldName);
        $markup .= $this->fieldBetween($fieldName);
        $markup .= $this->fieldEnd($fieldName);
        if ($type === 'radio' || $type === 'checkbox') {
            $markup .= $this->fieldLabel($fieldName);
        }
        $markup .= $this->fieldError($fieldName);
        $markup .= $this->fieldHelp($fieldName);
        $markup .= $this->fieldWrapEnd($fieldName);

        $f->setRendered(true);

        return $markup;
    }

    /** @inheritdoc */
    public function fieldWrapStart($fieldName)
    {
        $markup         = '';
        $f              = $fieldName instanceof FieldInterface ? $fieldName : $this->formInstance->getField($fieldName);
        $displayRules   = $f->getDisplayRules();
        $wrapAttributes = $displayRules['wrapAttributes'];

        if (isset($wrapAttributes['no-wrap']) && $wrapAttributes['no-wrap']) {
            return $markup;
        }

        //CSS classes
        if (empty($wrapAttributes['class'])) {
            $wrapAttributes['class'] = [];
        }
        $wrapAttributes['class'][] = 'form-group';
        $wrapAttributes['class'][] = $f->getTag() . '-wrap';
        $wrapAttributes['class'][] = $f->getName() . '-wrap';
        $errors                    = $f->getErrors();
        if (!empty($errors)) {
            $wrapAttributes['class'][] = 'has-error';
        }

        if (!empty($f)) {
            $markup .= '<div ' . \WonderWp\Framework\paramsToHtml($wrapAttributes) . '>';

            if (!empty($displayRules['before'])) {
                $markup .= $displayRules['before'];
            }
        }

        return $markup;
    }

    /** @inheritdoc */
    public function fieldLabel($fieldName)
    {
        $markup = '';
        $f      = ($fieldName instanceof FieldInterface) ? $fieldName : $this->formInstance->getField($fieldName);
        /** @var FormValidatorInterface $formValidator */
        $formValidator   = $this->container->offsetGet('wwp.forms.formValidator');
        $validationRules = !empty($f) ? $f->getValidationRules() : [];

        //fields that  use the label differently:
        if ($f->getTag() == 'button') {
            return $markup;
        }

        if (!empty($f)) {
            $displayRules = $f->getDisplayRules();
            if (!empty($displayRules['label'])) {
                $markup = '<label ' . (!empty($displayRules['labelAttributes']) ? \WonderWp\Framework\paramsToHtml($displayRules['labelAttributes']) : '') . '>';
                $markup .= $displayRules['label'];
                if ($formValidator::hasRule($validationRules, 'NotEmpty') && $f->getType() !== 'radio') {
                    $markup .= '<span class="required">*</span>';
                }
                $markup .= '</label>';
            }
        }

        return $markup;
    }

    /** @inheritdoc */
    public function fieldStart($fieldName)
    {
        $f = ($fieldName instanceof FieldInterface) ? $fieldName : $this->formInstance->getField($fieldName);

        $tag             = !empty($f) ? $f->getTag() : '';
        $type            = !empty($f) ? $f->getType() : [];
        $displayRules    = !empty($f) ? $f->getDisplayRules() : [];
        $validationRules = !empty($f) ? $f->getValidationRules() : [];
        $params          = !empty($displayRules['inputAttributes']) ? $displayRules['inputAttributes'] : [];
        /** @var FormValidator $formValidator */
        $formValidator = $this->container->offsetGet('wwp.forms.formValidator');
        $markup        = '';

        //Classes
        if (empty($params['class'])) {
            $params['class'] = [];
        }
        $params['class'][] = 'form-control';

        if ($tag == 'input') {
            $params['class'][] = $type;
        }
        if ($formValidator::hasRule($validationRules, 'NotEmpty')) {
            $params['required'] = '';
        }

        if ($tag == 'select') {
            $markup .= '<div class="select-style">';
        }

        if (method_exists($f, 'getGroup') && !empty($params) && !empty($params['name'])) {
            unset($params['name']);
        }

        //Open tag
        $markup .= '<' . $tag;

        //Type
        if ($tag == 'input') {
            $markup .= ' type="' . $f->getType() . '" ';
        }

        $isMultiple = !empty($params['multiple']);
        if ($isMultiple) {
            $params['name'] .= '[]';
        }

        //Add input parameters
        $markup .= ' ' . \WonderWp\Framework\paramsToHtml($params) . '';

        //Close opening tag
        if ($tag != 'input') {
            $markup .= '>';
        }

        return $markup;
    }

    /** @inheritdoc */
    public function fieldBetween($fieldName)
    {
        $markup       = '';
        $f            = $fieldName instanceof FieldInterface ? $fieldName : $this->formInstance->getField($fieldName);
        $displayRules = $f->getDisplayRules();

        if (!empty($f)) {
            $tag  = $f->getTag();
            $val  = $f->getValue();
            $type = $f->getType();

            //If group -> recurse
            if (method_exists($f, 'getGroup')) {
                $group = $f->getGroup();
                if (!empty($group)) {
                    foreach ($group as $fFromFroup) {
                        $markup .= $this->renderField($fFromFroup);
                    }
                }
            }

            if ($val instanceof \DateTime) {
                $val = $val->format('Y-m-d H:i:s');
            }

            //Value
            if ($tag == 'input') {
                if (is_array($val) || is_object($val)) {
                    $val = json_encode($val);
                }
                $markup .= ' value="' . $val . '" ';
                if ($type == 'checkbox') {
                    $cbValue = $displayRules['inputAttributes']['value'];
                    $markup  .= \checked($f->getValue(), $cbValue, false);
                }
            }
            if ($tag == 'textarea') {
                $markup .= $val;
            }
            if ($tag == 'button') {
                $markup .= $displayRules['label'];
            }

            //Select Options
            if ($tag == 'select') {
                /** @var $f SelectField */
                $opts       = $f->getOptions();
                $isMultiple = !empty($displayRules['inputAttributes']['multiple']);
                if (!empty($opts)) {
                    foreach ($opts as $key => $val) {
                        $markup .= $this->buildSelectOption($f, $val, $key, $isMultiple);
                    }
                }
            }
        }

        return $markup;
    }

    /**
     * @param SelectField $field
     * @param string      $label
     * @param mixed       $value
     * @param bool        $isMultiple
     *
     * @return string
     */
    public function buildSelectOption(SelectField $field, $label, $value, $isMultiple)
    {
        if (is_array($label)) {
            $markup = '<optgroup label="' . $value . '">';

            foreach ($label as $value => $realLabel) {
                $markup .= $this->buildSelectOption($field, $realLabel, $value, $isMultiple);
            }

            $markup .= '</optgroup>';

            return $markup;
        }

        if ($isMultiple) {
            $selected = is_array($field->getValue()) && in_array($value, $field->getValue()) ? 'selected' : '';
        } else {
            $selected = \selected($field->getValue(), $value, false);
        }

        return '<option value="' . $value . '" ' . $selected . ' >' . $label . '</option>';
    }

    /** @inheritdoc */
    public function fieldEnd($fieldName)
    {
        $f            = ($fieldName instanceof FieldInterface) ? $fieldName : $this->formInstance->getField($fieldName);
        $tag          = !empty($f) ? $f->getTag() : '';
        $displayRules = $f->getDisplayRules();

        $markup = '';

        if ($tag == 'input') {
            $markup .= ' />';
        } else {
            $markup .= '</' . $tag . '>';
        }

        if ($tag == 'select') {
            $markup .= '</div>'; // select-style
        }

        if (!empty($displayRules['after'])) {
            $markup .= $displayRules['after'];
        }

        return $markup;
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    public function fieldError($fieldName)
    {
        $markup       = '';
        $f            = ($fieldName instanceof FieldInterface) ? $fieldName : $this->formInstance->getField($fieldName);
        $errors       = $f->getErrors();
        $displayRules = $f->getDisplayRules();
        $fieldId      = !empty($displayRules) && !empty($displayRules['inputAttributes']) && !empty($displayRules['inputAttributes']['id']) ? $displayRules['inputAttributes']['id'] : '';
        if (!empty($errors)) {
            $markup .= '<label class="label-error" ' . ($fieldId ? 'for="' . $fieldId . '"' : '') . '>' . implode(', ', $errors) . '</label>';
        }

        return $markup;
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    public function fieldHelp($fieldName)
    {
        $markup = '';
        $f      = ($fieldName instanceof FieldInterface) ? $fieldName : $this->formInstance->getField($fieldName);

        $displayRules = $f->getDisplayRules();
        $help         = !empty($displayRules) && !empty($displayRules['help']) ? $displayRules['help'] : '';
        if (!empty($help)) {
            $markup .= '<span class="help">' . $help . '</span>';
        }

        return $markup;
    }

    /** @inheritdoc */
    public function fieldWrapEnd($fieldName)
    {
        $markup         = '';
        $f              = ($fieldName instanceof FieldInterface) ? $fieldName : $this->formInstance->getField($fieldName);
        $displayRules   = $f->getDisplayRules();
        $wrapAttributes = $displayRules['wrapAttributes'];

        if (isset($wrapAttributes['no-wrap']) && $wrapAttributes['no-wrap']) {
            return $markup;
        }

        return '</div>';
    }

    /** @inheritdoc */
    public function formEnd(array $optsEnd = [])
    {
        $markup = '';

        $defaultOptions = [
            'showSubmit'    => 1,
            'submitLabel'   => __('submit'),
            'btnAttributes' => [
                'type'  => 'submit',
                'class' => 'btn button',
            ],
        ];

        $options = \WonderWp\Framework\array_merge_recursive_distinct($defaultOptions, $optsEnd);

        if (!isset($options['showSubmit']) || $options['showSubmit'] == 1) {
            $markup .= '<div class="submitFormField">
                <button ' . \WonderWp\Framework\paramsToHtml($options['btnAttributes']) . '>' . $options['submitLabel'] . '</button>
            </div>';
        }

        if (empty($options['showFormTag']) || $options['showFormTag'] == 1) {
            $markup .= '</form>';
        }

        return $markup;
    }
}
