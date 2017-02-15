<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/08/2016
 * Time: 11:16
 */

namespace WonderWp\Forms\Fields;

use WonderWp\Forms\Validation\Validator;

class NonceField extends HiddenField{

    public function __construct($name, $value=null, array $displayRules=array(), array $validationRules=array())
    {
        $validationRules[] = Validator::WpNonce($name);
        $value = wp_create_nonce($name);
        parent::__construct($name, $value, $displayRules, $validationRules);
    }

}
