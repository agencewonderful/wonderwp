<?php

namespace WonderWp\Framework\Form;

use Respect\Validation\Rules\Length;
use Respect\Validation\Rules\Max;
use Respect\Validation\Rules\Min;
use Respect\Validation\Rules\NotEmpty;
use Respect\Validation\Rules\Regex;
use Respect\Validation\Validator;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Form\Field\FieldGroupInterface;
use WonderWp\Framework\Form\Field\FieldInterface;
use WonderWp\Framework\Form\Field\SelectField;
use function WonderWp\Framework\array_merge_recursive_distinct;
use function WonderWp\Framework\paramsToHtml;

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

    /** {@inheritdoc} */
    public function setFormInstance(FormInterface $form)
    {
        $this->formInstance = $form;

        return $this;
    }

    /** {@inheritdoc} */
    public function getFormInstance()
    {
        return $this->formInstance;
    }

    /** {@inheritdoc} */
    public function render(array $opts = [])
    {
        $markup = '';
        $optsStart = array_key_exists('formStart', $opts) ? $opts['formStart'] : [];
        $optsBeforeFields = array_key_exists('formBeforeFields', $opts) ? $opts['formBeforeFields'] : [];
        $optsEnd = array_key_exists('formEnd', $opts) ? $opts['formEnd'] : [];

        $markup .= $this->formStart($optsStart);
        $markup .= $this->formErrors();
        $markup .= $this->formBeforeFields($optsBeforeFields);

        $fields = $this->getFormInstance()->getFields();
        $allowedFields = array_key_exists('allowFields', $opts) ? $opts['allowFields'] : array_keys($fields);

        if (array_key_exists('excludeFields', $opts)) {
            $allowedFields = array_diff($allowedFields, $opts['excludeFields']);
        }

        foreach ($fields as $i => $field) {
            if (in_array($i, $allowedFields)) {
                $markup .= $this->renderField($field->getName());
            }
        }

        $groups = $this->getFormInstance()->getGroups();

        foreach ($groups as $group) {
            $markup .= $this->renderGroup($group);
        }

        $markup .= $this->formEnd($optsEnd);

        return $markup;
    }

    /** {@inheritdoc} */
    public function formStart(array $optsStart = [])
    {
        $defaultOptions = [
            'showFormTag' => true,
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'class' => ['wwpform', $this->formInstance->getName()],
        ];

        $options = array_merge_recursive_distinct($defaultOptions, $optsStart);

        if (!$options['showFormTag']) {
            return '';
        }

        unset($options['showFormTag']);

        $htmlOptions = paramsToHtml($options);

        return "<form {$htmlOptions}>";
    }

    /** {@inheritdoc} */
    public function formErrors()
    {
        $markup = '';

        $errors = $this->formInstance->getErrors();
        if (!empty($errors)) {
            $markup .= '<div class="form-errors"></div>';
        }

        return $markup;
    }

     /** {@inheritdoc} */
    public function formBeforeFields($optsBeforeFields = [])
    {
        // Init
        $markup = '';

        // Options given
        if (count($optsBeforeFields) > 0) {
            $markup .= '<div class="form-before-fields">';
            foreach ($optsBeforeFields as $str) {
                $markup .= $str;
            }
            $markup .= '</div>';
        }

        // Result
        return $markup;
    }

    /** {@inheritdoc} */
    public function renderGroup(FormGroup $group)
    {
        $markup = '';
        $fields = $group->getFields();

        if (!empty($fields)) {
            $displayRules = paramsToHtml($group->getDisplayRules());
            $markup .= "<fieldset {$displayRules}>";
            $markup .= '<legend class="hndle ui-sortable-handle">';
            $markup .= $group->getTitle();
            $markup .= '</legend>';
            $markup .= '<div class="inside">';

            foreach ($fields as $field) {
                $markup .= $this->renderField($field);
            }

            $markup .= '</div>';
            $markup .= '</fieldset>';
        }

        return $markup;
    }

    /** {@inheritdoc} */
    public function renderField($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field || $field->isRendered()) {
            return '';
        }

        $field->setRendered(true);

        $type = $field->getType();
        $markup = $this->fieldWrapStart($field);

        if (!in_array($type, ['radio', 'checkbox'], true)) {
            $markup .= $this->fieldLabel($field);
        }

        $markup .= $this->fieldStart($field);
        $markup .= $this->fieldBetween($field);
        $markup .= $this->fieldEnd($field);

        if (in_array($type, ['radio', 'checkbox'], true)) {
            $markup .= $this->fieldLabel($field);
        }

        $markup .= $this->fieldError($field);
        $markup .= $this->fieldHelp($field);
        $markup .= $this->fieldWrapEnd($field);

        return $markup;
    }

    /** {@inheritdoc} */
    public function fieldWrapStart($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        $displayRules = $field->getDisplayRules();
        $wrapAttributes = array_key_exists('wrapAttributes', $displayRules) ? $displayRules['wrapAttributes'] : [];

        if (array_key_exists('no-wrap', $wrapAttributes) && $wrapAttributes['no-wrap']) {
            return '';
        }

        // CSS classes
        if (!array_key_exists('class', $wrapAttributes)) {
            $wrapAttributes['class'] = [];
        }

        if (!is_array($wrapAttributes['class'])) {
            $wrapAttributes['class'] = [$wrapAttributes['class']];
        }

        $wrapAttributes['class'][] = 'form-group';
        $wrapAttributes['class'][] = "{$field->getTag()}-wrap";
        $wrapAttributes['class'][] = "{$field->getName()}-wrap";

        if (count($field->getErrors()) > 0) {
            $wrapAttributes['class'][] = 'has-error';
        }

        $markup = '';

        if (!empty($displayRules['before-wrap'])) {
            $markup .= $displayRules['before-wrap'];
        }

        $markup .= '<div '.paramsToHtml($wrapAttributes).'>';

        if (!empty($displayRules['before'])) {
            $markup .= $displayRules['before'];
        }

        return $markup;
    }

    /** {@inheritdoc} */
    public function fieldLabel($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        // Fields that  use the label differently:
        if ('button' === $field->getTag()) {
            return '';
        }

        $displayRules = $field->getDisplayRules();

        if (!array_key_exists('label', $displayRules) || false === $displayRules['label']) {
            return '';
        }

        $validationRules = $field->getValidationRules();
        $attributes = array_key_exists('labelAttributes', $displayRules) ? $displayRules['labelAttributes'] : [];
        $validationAttributes = $this->getValidationLabelAttributes($validationRules);
        $attributes = array_merge_recursive_distinct($validationAttributes, $attributes);

        $htmlAttributes = paramsToHtml($attributes);
        $markup = "<label {$htmlAttributes}>";
        $markup .= $this->getValidationLabelContent($displayRules['label'], $field);
        $markup .= '</label>';

        return $markup;
    }

    /** {@inheritdoc} */
    public function fieldStart($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        $tag = $field->getTag();
        $type = $field->getType();
        $displayRules = $field->getDisplayRules();
        $validationRules = $field->getValidationRules();
        $attributes = array_key_exists('inputAttributes', $displayRules) ? $displayRules['inputAttributes'] : [];
        $validationAttributes = $this->getValidationInputAttributes($validationRules);
        $attributes = array_merge_recursive_distinct($validationAttributes, $attributes);

        //Classes
        if (empty($attributes['class'])) {
            $attributes['class'] = [];
        }

        $attributes['class'][] = 'form-control';

        if ('input' === $tag) {
            $attributes['class'][] = $type;
        }

        if ($field instanceof FieldGroupInterface && array_key_exists('name', $attributes)) {
            unset($attributes['name']);
        }

        $markup = '';
        if ('select' === $tag) {
            $markup .= '<div class="select-style">';
        }

        //Open tag
        $markup .= "<{$tag}";

        //Type
        if ('input' === $tag) {
            $markup .= " type=\"{$field->getType()}\" ";
        }

        $isMultiple = !empty($attributes['multiple']);
        if ($isMultiple) {
            $attributes['name'] .= '[]';
        }

        //Add input parameters
        if ('div' == $tag) {
            if (isset($attributes['name'])) {
                unset($attributes['name']);
            }
        }
        $markup .= ' '.paramsToHtml($attributes);

        //Close opening tag
        if ('input' != $tag) {
            $markup .= '>';
        }

        return $markup;
    }

    /** {@inheritdoc} */
    public function fieldBetween($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        $markup = '';
        $displayRules = $field->getDisplayRules();

        $tag = $field->getTag();
        $val = $field->getValue();
        $type = $field->getType();

        //If group -> recurse
        if ($field instanceof FieldGroupInterface) {
            $group = $field->getGroup();

            foreach ($group as $fFromFroup) {
                $markup .= $this->renderField($fFromFroup);
            }
        }

        if ($val instanceof \DateTime) {
            $val = $val->format('Y-m-d H:i:s');
        }

        //Value
        if ('input' === $tag) {
            if (is_array($val) || is_object($val)) {
                $val = json_encode($val);
            }

            $markup .= " value=\"{$val}\" ";

            if ('checkbox' === $type) {
                $cbValue = $displayRules['inputAttributes']['value'];
                $markup .= \checked($field->getValue(), $cbValue, false);
            }
        }

        if ('textarea' === $tag) {
            $markup .= $val;
        }

        if ('button' === $tag && array_key_exists('label', $displayRules) && false !== $displayRules['label']) {
            $markup .= $displayRules['label'];
        }

        //Select Options
        if ('select' === $tag) {
            /** @var $field SelectField */
            $opts = $field->getOptions();
            $isMultiple = !empty($displayRules['inputAttributes']['multiple']);

            foreach ($opts as $key => $val) {
                $markup .= $this->buildSelectOption($field, $val, $key, $isMultiple);
            }
        }

        if ('div' === $tag && !$field instanceof FieldGroupInterface) {
            if (is_string($val)) {
                $markup .= $val;
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
    protected function buildSelectOption(SelectField $field, $label, $value, $isMultiple)
    {
        if (is_array($label)) {
            $markup = "<optgroup label=\"{$value}\">";

            foreach ($label as $value => $realLabel) {
                $markup .= $this->buildSelectOption($field, $realLabel, $value, $isMultiple);
            }

            $markup .= '</optgroup>';

            return $markup;
        }

        if ($isMultiple) {
            $selected = is_array($field->getValue()) && in_array($value, $field->getValue()) ? 'selected="selected"' : '';
        } else {
            $selected = \selected($field->getValue(), $value, false);
        }

        return "<option value=\"{$value}\" {$selected}>{$label}</option>";
    }

    /** {@inheritdoc} */
    public function fieldEnd($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        $tag = $field->getTag();
        $displayRules = $field->getDisplayRules();

        $markup = '';

        if ('input' === $tag) {
            $markup .= ' />';
        } else {
            $markup .= "</{$tag}>";
        }

        if ('select' === $tag) {
            $markup .= '</div>'; // select-style
        }

        if (array_key_exists('after', $displayRules)) {
            $markup .= $displayRules['after'];
        }

        return $markup;
    }

    /** {@inheritdoc} */
    public function fieldError($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        $errors = $field->getErrors();

        if (count($errors) < 1) {
            return '';
        }

        $displayRules = $field->getDisplayRules();
        $attributes = [
            'class' => 'label-error',
        ];

        if (array_key_exists('inputAttributes', $displayRules) && array_key_exists('id', $displayRules['inputAttributes'])) {
            $attributes['for'] = $displayRules['inputAttributes']['id'];
        }

        $attributesHtml = paramsToHtml($attributes);
        $markup = "<label {$attributesHtml}>";
        $markup .= implode(', ', $errors);
        $markup .= '</label>';

        return $markup;
    }

    /** {@inheritdoc} */
    public function fieldHelp($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        $displayRules = $field->getDisplayRules();

        if (!array_key_exists('help', $displayRules) || false === $displayRules['help']) {
            return '';
        }

        return "<span class=\"help\">{$displayRules['help']}</span>";
    }

    /** {@inheritdoc} */
    public function fieldWrapEnd($field)
    {
        if (is_string($field)) {
            $field = $this->formInstance->getField($field);
        }

        if (null === $field) {
            return '';
        }

        $displayRules = $field->getDisplayRules();
        $wrapAttributes = array_key_exists('wrapAttributes', $displayRules) ? $displayRules['wrapAttributes'] : [];

        if (array_key_exists('no-wrap', $wrapAttributes) && $wrapAttributes['no-wrap']) {
            return '';
        }

        $markup = '</div>';

        if (!empty($displayRules['after-wrap'])) {
            $markup .= $displayRules['after-wrap'];
        }

        return $markup;
    }

    /** {@inheritdoc} */
    public function formEnd(array $optsEnd = [])
    {
        $markup = '';

        $defaultOptions = [
            'showFormTag' => true,
            'showSubmit' => true,
            'submitLabel' => __('submit'),
            'showReset' => false,
            'resetLabel' => __('reset'),
            'btnAttributes' => [
                'type' => 'submit',
                'class' => 'btn button',
            ],
            'resetbtnAttributes' => [
                'type' => 'reset',
                'class' => 'btn button btn-secondary',
            ],
        ];

        $options = array_merge_recursive_distinct($defaultOptions, $optsEnd);

        if ($options['showSubmit']) {
            $markup .= '<div class="submitFormField">
                <button '.paramsToHtml($options['btnAttributes']).'>'.$options['submitLabel'].'</button>';
            if ($options['showReset']) {
                $markup .= '  <button '.paramsToHtml($options['resetbtnAttributes']).'>'.$options['resetLabel'].'</button>';
            }
            $markup .= '</div>';
        }

        if ($options['showFormTag']) {
            $markup .= '</form>';
        }

        return $markup;
    }

    /**
     * @param Validator[] $validationRules
     *
     * @return array
     */
    protected function getValidationInputAttributes(array $validationRules)
    {
        $attributes = [];
        $validator = $this->getFormValidator();

        if ($validator::hasRule($validationRules, NotEmpty::class)) {
            $attributes['required'] = 'required';
        }

        $lengthRule = $validator::getRule($validationRules, Length::class);
        if ($lengthRule instanceof Length && null !== $lengthRule->maxValue) {
            $attributes['maxlength'] = $lengthRule->maxValue;
        }

        $maxRule = $validator::getRule($validationRules, Max::class);
        if ($maxRule instanceof Max) {
            $attributes['max'] = $maxRule->interval;
        }

        $minRule = $validator::getRule($validationRules, Min::class);
        if ($minRule instanceof Min) {
            $attributes['min'] = $minRule->interval;
        }

        $regexRule = $validator::getRule($validationRules, Regex::class);
        if ($regexRule instanceof Regex) {
            $attributes['pattern'] = $regexRule->regex;
        }

        return $attributes;
    }

    /**
     * @param Validator[] $validationRules
     *
     * @return array
     */
    protected function getValidationLabelAttributes(array $validationRules)
    {
        return [];
    }

    /**
     * @param string         $label
     * @param FieldInterface $field
     *
     * @return string
     */
    protected function getValidationLabelContent($label, FieldInterface $field)
    {
        $validator = $this->getFormValidator();
        $validationRules = $field->getValidationRules();

        if ('radio' !== $field->getType() && $validator::hasRule($validationRules, NotEmpty::class)) {
            $label .= '<span class="required">*</span>';
        }

        return $label;
    }

    /**
     * @return FormValidator
     */
    protected function getFormValidator()
    {
        return $this->container->offsetGet('wwp.forms.formValidator');
    }
}
