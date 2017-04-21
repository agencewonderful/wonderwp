<?php

namespace WonderWp\Framework\Form;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AbstractComposite;
use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validatable;

class FormValidator implements FormValidatorInterface
{
    /** @var FormInterface */
    protected $formInstance;

    /** @inheritdoc */
    public function validate(array $data, $translationDomain = 'default')
    {
        $fields = $this->formInstance->getFields();

        $errors = [];

        foreach ($fields as $field) {
            $fieldErrors     = [];
            $fieldData       = array_key_exists($field->getName(), $data) ? $data[$field->getName()] : null;
            $validationRules = $field->getValidationRules();
            $displayRules    = $field->getDisplayRules();
            $name            = array_key_exists('label', $displayRules) ? $displayRules['label'] : $field->getName();

            foreach ($validationRules as $validator) {
                /** @var Validatable[] $rules */
                $rules = $validator->setName($name)->getRules();

                foreach ($rules as $rule) {
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

            if (!empty($fieldErrors)) {
                $field->setErrors($fieldErrors);
                $errors[$field->getName()] = $fieldErrors;
            }
        }

        $this->formInstance->setErrors($errors);

        return $errors;
    }

    /** @inheritdoc */
    public static function hasRule(array $validationRules, $ruleName)
    {
        return static::getRule($validationRules, $ruleName) !== null;
    }

    /** @inheritdoc */
    public static function getRule(array $validationRules, $ruleName)
    {
        foreach ($validationRules as $rule) {
            if ($rule instanceof AbstractWrapper) {
                $rule = $rule->getValidatable();
            }

            $reflection = new \ReflectionClass($rule);

            if ($ruleName === $reflection->getShortName() || $ruleName === $reflection->getName()) {
                return $rule;
            }

            if ($rule instanceof AbstractComposite) {
                if (null !== $rule = static::getRule($rule->getRules(), $ruleName)) {
                    return $rule;
                }
            }
        }

        return null;
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
