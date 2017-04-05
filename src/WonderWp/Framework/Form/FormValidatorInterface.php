<?php

namespace WonderWp\Framework\Form;

interface FormValidatorInterface
{
    /**
     * @param FormInterface $form
     *
     * @return static
     */
    public function setFormInstance(FormInterface $form);

    /**
     * @return FormInterface
     */
    public function getFormInstance();

    /**
     * @param array  $data
     * @param string $translationDomain
     *
     * @return array
     */
    public function validate(array $data, $translationDomain = 'default');

    /**
     * @param array  $validationRules
     * @param string $ruleName
     *
     * @return bool
     */
    public static function hasRule(array $validationRules, $ruleName);
}
