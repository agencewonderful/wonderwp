<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/03/2017
 * Time: 15:38
 */

namespace WonderWp\Forms;


use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Fields\HiddenField;

class FormViewReadOnly extends FormView
{
    public function formStart($optsStart = array())
    {
        $defaultOptions = array(
            'class'=>['wwpReadonlyForm']
        );
        $options = array_merge_recursive($defaultOptions, $optsStart);
        return parent::formStart($options);
    }

    public function renderField($fieldName)
    {
        $markup = '';
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);

        if($f->getRendered()){ return $markup; }

        $type = (is_object($f)) ? $f->getType() : null;

        $markup .= $this->fieldWrapStart($fieldName);

        $markup .= $this->fieldLabel($fieldName);

        $markup .= $this->fieldStart($fieldName);
        $markup .= $this->fieldBetween($fieldName);
        $markup .= $this->fieldEnd($fieldName);

        $markup .= $this->fieldWrapEnd($fieldName);

        $f->setRendered(true);

        return $markup;
    }

    public function fieldStart($fieldName){
        return '<span class="readOnlyVal">';
    }

    public function fieldBetween($fieldName)
    {
        $markup = '';
        /** @var AbstractField $f */
        $f = ($fieldName instanceof AbstractField) ? $fieldName : $this->_formInstance->getField($fieldName);

        if (!empty($f)) {
            $tag = $f->getTag();
            $val = $f->getValue();
            $type = $f->getType();
            if($val instanceof \DateTime){
                $val = $val->format('d/m/Y');
            }

            if($f instanceof HiddenField){
                return '';
            }

            if (method_exists($f, 'getOptions') && !empty($f->getOptions())) {
                $opts = $f->getOptions();
                if(!empty($opts[$val])){
                    $markup.=$opts[$val];
                }
            } else {
                $markup.=print_r($val,true);
            }
        }

        return $markup;
        /*
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
            if ($tag == 'input' || $tag == 'textarea') {

                \WonderWp\trace($f->getName(),$val);

                $markup .= $val;
            }
            if($tag=='button'){
                $markup.=$displayRules['label'];
            }

            //Select Options
            if ($tag == 'select') {
                /** @var $f SelectField */
                /*$opts = $f->getOptions();
                $isMultiple = !empty($displayRules['inputAttributes']['multiple']);
                if (!empty($opts)) {
                    foreach ($opts as $key => $val) {
                        $markup .= $this->buildSelectOption($f, $val, $key, $isMultiple);
                    }
                }
            }
        }
        return $markup;*/
    }

    public function fieldEnd($fieldName)
    {
        return '</span>';
    }

}
