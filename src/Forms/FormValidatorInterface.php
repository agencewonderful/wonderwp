<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/08/2016
 * Time: 17:24
 */

namespace WonderWp\Forms;

interface FormValidatorInterface {

    public function setFormInstance(FormInterface $form);

    public function getFormInstance();

    public function validate(array $data, $translationDomain = 'default');

    public static function hasRule(array $validationRules, $ruleName);

}
