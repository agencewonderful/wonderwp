<?php

namespace WonderWp\Framework\Form;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Rules\Optional;
use Respect\Validation\Validator;
use WonderWp\Framework\Form\Field\FieldInterface;

class FormValidator implements FormValidatorInterface
{
    /** @var FormInterface */
    protected $formInstance;

    /** @inheritdoc */
    public function validate(array $data, $translationDomain = 'default')
    {
        /** @var FieldInterface[] $fields */
        $fields = $this->formInstance->getFields();

        $errors = [];

        if (!empty($fields)) {
            foreach ($fields as $f) {
                /** @var Validator[] $validationRules */
                $validationRules = $f->getValidationRules();

                $fieldData = !empty($data[$f->getName()]) ? $data[$f->getName()] : null;

                $fieldErrors = [];

                if (!empty($validationRules)) {
                    foreach ($validationRules as $validator) {
                        $displayRules = $f->getDisplayRules();
                        $name         = array_key_exists('label', $displayRules) ? $displayRules['label'] : $f->getName();
                        $rules        = $validator->setName($name)->getRules();
                        if (!empty($rules)) {
                            foreach ($rules as $rule) {
                                /** @var $rule AbstractRule */
                                try {
                                    $rule->assert($fieldData);
                                } catch (ValidationException $exception) {
                                    if (!empty($validationRule[1])) {
                                        $errorMsg = $validationRule[1];
                                    } else {
                                        $errorMsg = $exception
                                            ->setTemplate(__($exception->getTemplate(), $translationDomain))
                                            ->getMainMessage()
                                        ;
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

        $this->formInstance->setErrors($errors);

        return $errors;
    }

    /** @inheritdoc */
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
                            foreach ($subRules as $sr) {
                                $validationClass = get_class($sr);
                                if ($validationClass == $ruleName) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param FormInterface $form
     *
     * @return static
     */
    public function setFormInstance(FormInterface $form)
    {
        $this->formInstance = $form;

        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getFormInstance()
    {
        return $this->formInstance;
    }
}

