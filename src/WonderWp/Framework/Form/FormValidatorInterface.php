<?php

namespace WonderWp\Framework\Form;

interface FormValidatorInterface
{
    public function setFormInstance(FormInterface $form);

    public function getFormInstance();

    public function validate(array $data, $translationDomain = 'default');

    public static function hasRule(array $validationRules, $ruleName);
}
