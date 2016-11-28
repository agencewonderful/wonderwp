<?php

namespace WonderWp\Forms;

use WonderWp\DI\Container;
use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Fields\FieldInterface;
use WonderWp\Forms\Fields\SelectField;
use Pimple\Container as PContainer;

class FormView implements FormViewInterface
{

    /**
     * @var FormInterface
     */
    protected $_formInstance;

    /**
     * @var PContainer
     */
    protected $_container;

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
        $fields = $this->getFormInstance()->getFields();
        if (!empty($fields)) {
            foreach ($fields as $f) {
                /* @var $f fieldInterface */
                $markup .= $this->renderField($f->getName());
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

        if($f->getRendered()){ return $markup; }

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
        $markup .= $this->fieldHelp($fieldName);
        $markup .= $this->fieldWrapEnd($fieldName);

        $f->setRendered(true);

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
        /** @var FormValidatorInterface $formValidator */
        $formValidator = $this->_container->offsetGet('wwp.forms.formValidator');
        $validationRules = !empty($f) ? $f->getValidationRules() : array();

        //fields that  use the label differently:
        if($f->getTag()=='button'){ return $markup; }

        if (!empty($f)) {
            $displayRules = $f->getDisplayRules();
            if (!empty($displayRules['label'])) {
                $markup = '<label ' . (!empty($displayRules['labelAttributes']) ? \WonderWp\paramsToHtml($displayRules['labelAttributes']) : '') . '>';
                    $markup.= $displayRules['label'];
                    if($formValidator::hasRule($validationRules,'NotEmpty') && $f->getType()!=='radio'){
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
        $markup ='';

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

        if ($tag == 'select') {
            $markup.='<div class="select-style">';
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
                if(is_array($val) || is_object($val)){ $val = json_encode($val); }
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
                            $selected = is_array($f->getValue()) && in_array($key,$f->getValue()) ? 'selected' : '';
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

        if ($tag == 'select') {
            $markup.='</div>'; // select-style
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

    public function fieldHelp($fieldName)
    {
        $markup = '';
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);

        $displayRules = $f->getDisplayRules();
        $help = !empty($displayRules) && !empty($displayRules['help']) ? $displayRules['help'] : '';
        if (!empty($help)) {
            $markup .= '<span class="help">' . $help . '</span>';
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

        $defaultOptions = array(
            'showSubmit'=>1,
            'submitLabel'=>__('submit'),
            'btnAttributes'=>array(
                'type'=>'submit',
                'class'=>'btn button'
            )
        );

        $options = \WonderWp\array_merge_recursive_distinct($defaultOptions, $optsEnd);

        if (!isset($options['showSubmit']) || $options['showSubmit'] == 1) {
            $markup .= '<div class="submitFormField">
                <button ' . \WonderWp\paramsToHtml($options['btnAttributes']) . '>'.$options['submitLabel'].'</button>
            </div>';
        }

        if (empty($options['showFormTag']) || $options['showFormTag'] == 1) {
            $markup .= '</form>';
        }
        return $markup;
    }

}