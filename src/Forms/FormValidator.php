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
use Respect\Validation\Rules\Optional;
use Respect\Validation\Validator;
use WonderWp\Forms\Fields\FieldInterface;

class FormValidator implements FormValidatorInterface
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
        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getFormInstance()
    {
        return $this->_formInstance;
    }

    public function validate(array $data)
    {
        $fields = $this->_formInstance->getFields();

        $errors = array();

        if (!empty($fields)) {
            foreach ($fields as $f) {
                /* @var $f fieldInterface */
                $validationRules = $f->getValidationRules();

                $fieldData = !empty($data[$f->getName()]) ? $data[$f->getName()] : null;

                $fieldErrors = array();

                if (!empty($validationRules)) {
                    foreach ($validationRules as $validator) {
                        $rules = $validator->getRules();
                        if (!empty($rules)) {
                            foreach ($rules as $rule) {
                                /** @var $rule AbstractRule */
                                try {
                                    $rule->assert($fieldData);
                                } catch (ValidationException $exception) {
                                    if (!empty($validationRule[1])) {
                                        $errorMsg = $validationRule[1];
                                    } else {
                                        $errorMsg = $exception->getMainMessage();
                                    }
                                    $fieldErrors[] = $errorMsg;
                                }
                            }
                        }
                    }
                }
                if (!empty($fieldErrors)) {
                    $f->setErrors($fieldErrors);
                    $errors[$f->getName()] = $fieldErrors;
                }
            }
        }

        $this->_formInstance->setErrors($errors);

        return $errors;
    }

    public static function hasRule(array $validationRules, $ruleName)
    {
        $ruleName = 'Respect\Validation\Rules\\' . $ruleName;

        if (!empty($validationRules)) {
            foreach ($validationRules as $validator) {
                /** @var Validator $validator */
                $rules = $validator->getRules();
                if (!empty($rules)) {
                    foreach ($rules as $r) {
                        $validationClass = get_class($r);
                        if ($validationClass == $ruleName) {
                            return true;
                        }

                        if ($validationClass == Optional::class) {
                            /** @var Optional $r */
                            $subRules = $r->getValidatable()->getRules();
                            if (!empty($subRules)) {
                                foreach ($subRules as $sr) {
                                    $validationClass = get_class($r);
                                    if ($validationClass == $ruleName) {
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

}