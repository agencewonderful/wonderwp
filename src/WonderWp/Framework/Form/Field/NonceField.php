<?php

namespace WonderWp\Framework\Form\Field;

use WonderWp\Framework\Form\Validation\Validator;

class NonceField extends HiddenField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [])
    {
        $validationRules[] = Validator::WpNonce($name);
        $value             = wp_create_nonce($name);

        parent::__construct($name, $value, $displayRules, $validationRules);
    }
}
