<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 08/09/2017
 * Time: 10:39
 */

namespace WonderWp\Framework\Form\Field;

class EditorField extends AbstractField
{
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [])
    {
        $this->tag  = 'div';
        ob_start();
        wp_editor(stripslashes($value), $name);
        $value = ob_get_contents();
        ob_end_clean();
        return parent::__construct($name, $value, $displayRules, $validationRules);
    }
}
