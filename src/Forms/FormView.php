<?php

namespace WonderWp\Forms;

use WonderWp\DI\Container;
use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Fields\FieldInterface;
use WonderWp\Forms\Fields\SelectField;

class FormView implements FormViewInterface
{

    /**
     * @var FormInterface
     */
    private $_formInstance;

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setFormInstance(FormInterface $form)
    {
        $this->_formInstance = $form;
        $this->_container = Container::getInstance();
        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getFormInstance()
    {
        return $this->_formInstance;
    }

    public function render($opts = array())
    {
        $markup = '';
        $optsStart = !empty($opts['formStart']) ? $opts['formStart'] : array();
        $optsEnd = !empty($opts['formEnd']) ? $opts['formEnd'] : array();
        $markup .= $this->formStart($optsStart);
        $markup .= $this->formErrors();
        $fields = $this->_formInstance->getFields();
        if (!empty($fields)) {
            foreach ($fields as $f) {
                /* @var $f fieldInterface */
                $markup .= $this->renderField($f->getName());
            }
        }
        $groups = $this->_formInstance->getGroups();
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $markup .= $this->renderGroup($group);
            }
        }
        $markup .= $this->formEnd($optsEnd);

        return $markup;
    }

    public function formStart($optsStart = array())
    {
        $defaultOptions = array(
            'showFormTag' => 1,
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'class'=>['wwpform',$this->_formInstance->getName()]
        );
        $options = array_merge_recursive($defaultOptions, $optsStart);
        $markup = '';
        if ($options['showFormTag'] == 1) {
            if (!empty($options['showFormTag'])) {
                unset($options['showFormTag']);
            }
            $markup .= '<form ' . \WonderWp\paramsToHtml($options) . '>';
        }
        return $markup;
    }

    public function formErrors()
    {
        $markup = '';
        $errors = $this->_formInstance->getErrors();
        if (!empty($errors)) {
            $markup .= '<div class="form-errors"></div>';
        }
        return $markup;
    }

    public function renderGroup(FormGroup $group)
    {
        $markup = '';
        $fields = $group->getFields();
        if (!empty($fields)) {
            $markup.='<fieldset '.\WonderWp\paramsToHtml($group->getDisplayRules()).'>
            <legend class="hndle ui-sortable-handle">'.$group->getTitle().'</legend>            
            <div class="inside">';
            foreach ($fields as $field) {
                $markup .= $this->renderField($field);
            }
            $markup.='</div></fieldset>';
        }
        return $markup;
    }

    public function renderField($fieldName)
    {
        $markup = '';
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);
        $type = (is_object($f)) ? $f->getType() : null;

        $markup .= $this->fieldWrapStart($fieldName);
        if($type!=='radio' && $type!=='checkbox') {
            $markup .= $this->fieldLabel($fieldName);
        }
        $markup .= $this->fieldStart($fieldName);
        $markup .= $this->fieldBetween($fieldName);
        $markup .= $this->fieldEnd($fieldName);
        if($type==='radio' || $type==='checkbox') {
            $markup .= $this->fieldLabel($fieldName);
        }
        $markup .= $this->fieldError($fieldName);
        $markup .= $this->fieldWrapEnd($fieldName);

        return $markup;
    }

    public function fieldWrapStart($fieldName)
    {
        $markup = '';
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);
        $displayRules = $f->getDisplayRules();
        $wrapAttributes = $displayRules['wrapAttributes'];

        if(isset($wrapAttributes['no-wrap']) && $wrapAttributes['no-wrap']){ return $markup; }

        //CSS classes
        if (empty($wrapAttributes['class'])) {
            $wrapAttributes['class'] = array();
        }
        $wrapAttributes['class'][] = 'form-group';
        $wrapAttributes['class'][] = $f->getTag().'-wrap';
        $wrapAttributes['class'][] = $f->getName().'-wrap';
        $errors = $f->getErrors();
        if (!empty($errors)) {
            $wrapAttributes['class'][] = 'has-error';
        }

        if (!empty($f)) {
            $markup .= '<div ' . \WonderWp\paramsToHtml($wrapAttributes) . '>';

            if (!empty($displayRules['before'])) {
                $markup .= $displayRules['before'];
            }
        }
        return $markup;
    }

    public function fieldLabel($fieldName)
    {
        $markup = '';
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);
        $formValidator = $this->_container->offsetGet('wwp.forms.formValidator');
        $validationRules = !empty($f) ? $f->getValidationRules() : array();

        //fields that  use the label differently:
        if($f->getTag()=='button'){ return $markup; }

        if (!empty($f)) {
            $displayRules = $f->getDisplayRules();
            if (!empty($displayRules['label'])) {
                $markup = '<label ' . (!empty($displayRules['labelAttributes']) ? \WonderWp\paramsToHtml($displayRules['labelAttributes']) : '') . '>';
                    $markup.= $displayRules['label'];
                    if($formValidator::hasRule($validationRules,'NotEmpty')){
                        $markup.='<span class="required">*</span>';
                    }
                $markup.= '</label>';
            }
        }
        return $markup;
    }

    public function fieldStart($fieldName)
    {
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);

        $tag = !empty($f) ? $f->getTag() : '';
        $type = !empty($f) ? $f->getType() : array();
        $displayRules = !empty($f) ? $f->getDisplayRules() : array();
        $validationRules = !empty($f) ? $f->getValidationRules() : array();
        $params = !empty($displayRules['inputAttributes']) ? $displayRules['inputAttributes'] : array();
        /** @var FormValidator $formValidator */
        $formValidator = $this->_container->offsetGet('wwp.forms.formValidator');

        //Classes
        if (empty($params['class'])) {
            $params['class'] = array();
        }
        $params['class'][] = 'form-control';

        if ($tag == 'input') {
            $params['class'][] = $type;
        }
        if($formValidator::hasRule($validationRules,'NotEmpty')){
            $params['required'] = '';
        }



        //Open tag
        $markup = '<' . $tag;

        //Type
        if ($tag == 'input') {
            $markup .= ' type="' . $f->getType() . '" ';
        }

        $isMultiple = !empty($params['multiple']);
        if($isMultiple){ $params['name'].='[]'; }

        //Add input parameters
        $markup .= ' ' . \WonderWp\paramsToHtml($params) . '';

        //Close opening tag
        if ($tag != 'input') {
            $markup .= '>';
        }
        return $markup;
    }

    public function fieldBetween($fieldName)
    {
        $markup = '';
        /** @var AbstractField $f */
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);
        $displayRules = $f->getDisplayRules();

        if (!empty($f)) {
            $tag = $f->getTag();
            $val = $f->getValue();
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
                $markup .= ' value="' . $val . '" ';
                if ($type == 'checkbox') {
                    $cbValue = $displayRules['inputAttributes']['value'];
                    $markup .= \checked($f->getValue(), $cbValue, false);
                }
            }
            if ($tag == 'textarea') {
                $markup .= $val;
            }
            if($tag=='button'){
                $markup.=$displayRules['label'];
            }

            //Select Options
            if ($tag == 'select') {
                /** @var $f SelectField */
                $opts = $f->getOptions();
                $isMultiple = !empty($displayRules['inputAttributes']['multiple']);
                if (!empty($opts)) {
                    foreach ($opts as $key => $val) {
                        if($isMultiple){
                            $selected = in_array($key,$f->getValue()) ? 'selected' : '';
                        } else {
                            $selected = \selected($f->getValue(), $key, false);
                        }
                        $markup .= '<option value="' . $key . '" ' . $selected . ' >' . $val . '</option>';
                    }
                }
            }
        }
        return $markup;
    }


    public function fieldEnd($fieldName)
    {
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);
        $tag = !empty($f) ? $f->getTag() : '';
        $displayRules = $f->getDisplayRules();

        $markup = '';

        if ($tag == 'input') {
            $markup .= ' />';
        } else {
            $markup .= '</' . $tag . '>';
        }

        if (!empty($displayRules['after'])) {
            $markup .= $displayRules['after'];
        }

        return $markup;
    }

    public function fieldError($fieldName)
    {
        $markup = '';
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);
        $errors = $f->getErrors();
        $displayRules = $f->getDisplayRules();
        $fieldId = !empty($displayRules) && !empty($displayRules['inputAttributes']) && !empty($displayRules['inputAttributes']['id']) ? $displayRules['inputAttributes']['id'] : '';
        if (!empty($errors)) {
            $markup .= '<label class="label-error" ' . ($fieldId ? 'for="' . $fieldId . '"' : '') . '>' . implode(', ', $errors) . '</label>';
        }
        return $markup;
    }

    public function fieldWrapEnd($fieldName)
    {
        $markup = '';
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);
        $displayRules = $f->getDisplayRules();
        $wrapAttributes = $displayRules['wrapAttributes'];

        if(isset($wrapAttributes['no-wrap']) && $wrapAttributes['no-wrap']){ return $markup; }

        return '</div>';
    }

    public function formEnd($optsEnd = array())
    {
        $markup = '';

        if (!isset($optsEnd['showSubmit']) || $optsEnd['showSubmit'] == 1) {
            $markup .= '<div class="submitFormField">
                <input type="submit" class="btn button"/>
            </div>';
        }

        if (empty($optsEnd['showFormTag']) || $optsEnd['showFormTag'] == 1) {
            $markup .= '</form>';
        }
        return $markup;
    }

}