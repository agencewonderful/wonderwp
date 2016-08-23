<?php

namespace WonderWp\Forms;

use WonderWp\Forms\Fields\FieldInterface;
use WonderWp\Forms\Fields\SelectField;

class FormView implements FormViewInterface{

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
        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getFormInstance()
    {
        return $this->_formInstance;
    }

    public function render($opts=array())
    {
        $markup='';
        $optsStart = !empty($opts['formStart']) ? $opts['formStart'] : array();
        $markup .= $this->formStart($optsStart);
        $markup .= $this->formErrors();
        $fields = $this->_formInstance->getFields();
        if(!empty($fields)){ foreach($fields as $f){
            /* @var $f fieldInterface */
            $markup.= $this->renderField($f->getName());
        }}
        $markup .= $this->formEnd();

        return $markup;
    }

    public function formStart($optsStart = array())
    {
        $defaultOptions = array(
            'showFormTag'=>1,
            'method'=>'post',
            'enctype'=>'multipart/form-data'
        );
        $options = array_merge_recursive($defaultOptions,$optsStart);
        $markup='';
        if($options['showFormTag']==1) {
            if(!empty($options['showFormTag'])){ unset($options['showFormTag']); }
            $markup.='<form '.$this->paramsToHtml($options).'>';
        }
        return $markup;
    }

    public function formErrors(){
        $markup='';
        $errors = $this->_formInstance->getErrors();
        if(!empty($errors)){
            $markup.='<div class="form-errors"></div>';
        }
        return $markup;
    }

    public function renderField($fieldName)
    {
        $markup='';

        $markup.=$this->fieldWrapStart($fieldName);
        $markup.=$this->fieldLabel($fieldName);
        $markup.=$this->fieldStart($fieldName);
        $markup.=$this->fieldBetween($fieldName);
        $markup.=$this->fieldEnd($fieldName);
        $markup.=$this->fieldError($fieldName);
        $markup.=$this->fieldWrapEnd();

        return $markup;
    }

    public function fieldWrapStart($fieldName)
    {
        $markup = '';
        $f = $this->_formInstance->getField($fieldName);
        $displayRules = $f->getDisplayRules();
        $wrapAttributes = $displayRules['wrapAttributes'];

        //CSS classes
        if (empty($wrapAttributes['class'])) {
            $wrapAttributes['class'] = array();
        }
        $wrapAttributes['class'][] = 'form-group';
        $errors = $f->getErrors();
        if(!empty($errors)){
            $wrapAttributes['class'][] = 'has-error';
        }

        if(!empty($f)){
            $markup.='<div '.$this->paramsToHtml($wrapAttributes).'>';
        }
        return $markup;
    }

    public function fieldLabel($fieldName)
    {
        $markup = '';
        $f = $this->_formInstance->getField($fieldName);
        if(!empty($f)) {
            $displayRules = $f->getDisplayRules();
            if(!empty($displayRules['label'])) {
                $markup = '<label ' . (!empty($displayRules['labelAttributes']) ? $this->paramsToHtml($displayRules['labelAttributes']) : '') . '>' . $displayRules['label'] . '</label>';
            }
        }
        return $markup;
    }

    public function fieldStart($fieldName)
    {
        $f = $this->_formInstance->getField($fieldName);

        $tag = !empty($f) ? $f->getTag() : '';
        $type = !empty($f) ? $f->getType() : array();
        $displayRules = !empty($f) ? $f->getDisplayRules() : array();
        $params = !empty($displayRules['inputAttributes']) ? $displayRules['inputAttributes'] : array();


        //Classes
        if (empty($params['class'])) {
            $params['class'] = array();
        }
        $params['class'][] = 'form-control';

        if($tag=='input') {
            $params['class'][] = $type;
        }

        //Open tag
        $markup ='<'.$tag;

        //Type
        if($tag=='input'){
            $markup.=' type="'.$f->getType().'" ';
        }

        //Add input parameters
        $markup.=' '.$this->paramsToHtml($params).'';

        //Close opening tag
        if($tag!='input'){
            $markup .= '>';
        }
        return $markup;
    }

    public function fieldBetween($fieldName)
    {
        $markup = '';
        $f = $this->_formInstance->getField($fieldName);
        $displayRules = $f->getDisplayRules();

        if(!empty($f)) {
            $tag = $f->getTag();
            $val = $f->getValue();
            $type = $f->getType();

            if($val instanceof \DateTime){
                $val = $val->format('Y-m-d H:i:s');
            }

            //Value
            if($tag=='input'){
                $markup.=' value="'.$val.'" ';
                if($type=='checkbox'){
                    $cbValue = $displayRules['inputAttributes']['value'];
                    $markup .= \checked( $f->getValue(), $cbValue, false );
                }
            }
            if($tag=='textarea'){
                $markup.=$val;
            }

            //Select Options
            if($tag=='select'){
                /** @var $f SelectField */
                $opts = $f->getOptions();
                if(!empty($opts)){ foreach($opts as $key=>$val){
                    $markup.='<option value="'.$key.'" '.\selected( $f->getValue(), $key, false ).' >'.$val.'</option>';
                }}
            }
        }
        return $markup;
    }


    public function fieldEnd($fieldName)
    {
        $f = $this->_formInstance->getField($fieldName);
        $tag = !empty($f) ? $f->getTag() : '';

        $markup='';

        if($tag=='input'){ $markup = ' />'; }
        else {
            $markup ='</'.$tag.'>';
        }

        return $markup;
    }

    public function fieldError($fieldName)
    {
        $markup='';
        $f = $this->_formInstance->getField($fieldName);
        $errors = $f->getErrors();
        $displayRules = $f->getDisplayRules();
        $fieldId = !empty($displayRules) && !empty($displayRules['inputAttributes']) && !empty($displayRules['inputAttributes']['id']) ? $displayRules['inputAttributes']['id'] : '';
        if(!empty($errors)){
            $markup.='<label class="label-error" '.($fieldId ? 'for="'.$fieldId.'"' : '').'>'.implode(', ',$errors).'</label>';
        }
        return $markup;
    }

    public function fieldWrapEnd()
    {
        return '</div>';
    }

    public function formEnd($optsEnd=array())
    {
        $markup='';

        if(empty($optsEnd['showSubmit']) || $optsEnd['showSubmit']==1) {
            $markup .= '<div class="submitFormField">
                <input type="submit" class="btn button"/>
            </div>';
        }

        if(empty($optsEnd['showFormTag']) || $optsEnd['showFormTag']==1) {
            $markup .= '</form>';
        }
        return $markup;
    }

    public function paramsToHtml($params){
        $paramsHtml = '';
        if(!empty($params)){ foreach($params as $key=>$val){
            if(is_array($val)){ $val = implode(' ',$val); }
            $paramsHtml.=' '.$key.' = "'.$val.'"';
        }}
        return $paramsHtml;
    }
}