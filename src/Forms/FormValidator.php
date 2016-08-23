<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 10/08/2016
 * Time: 17:14
 */

namespace WonderWp\Forms;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AbstractRule;
use WonderWp\Forms\Fields\FieldInterface;

class FormValidator implements FormValidatorInterface{

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

    public function validate(array $data){
        $fields = $this->_formInstance->getFields();

        $errors = array();

        if(!empty($fields)){ foreach($fields as $f){
            /* @var $f fieldInterface */
            $validationRules = $f->getValidationRules();

            $fieldData = !empty($data[$f->getName()]) ? $data[$f->getName()] : null;
            $f->setValue($fieldData);
            $fieldErrors = array();

            if(!empty($validationRules)){ foreach($validationRules as $validationRule){
                $rule = $validationRule[0];
                /** @var $rule AbstractRule */
                try {
                    $rule->assert($fieldData);
                } catch(ValidationException $exception) {
                    if(!empty($validationRule[1])){ $errorMsg = $validationRule[1]; }
                    else { $errorMsg = $exception->getFullMessage(); }
                    $fieldErrors[] = $errorMsg;
                }
            }}
            if(!empty($fieldErrors)) {
                $f->setErrors($fieldErrors);
                $errors[$f->getName()] = $fieldErrors;
            }
        }}

        $this->_formInstance->setErrors($errors);

        return $errors;
    }

}