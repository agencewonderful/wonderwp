<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/08/2016
 * Time: 17:11
 */
namespace WonderWp\Forms\Fields;

class HiddenField extends InputField{

    /**
     * HiddenField constructor.
     * @param $name
     * @param null $value
     * @param array $displayRules
     * @param array $validationRules
     * @return HiddenField
     */
    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array()){
        if(!empty($displayRules['label'])){ unset($displayRules['label']); }
        parent::__construct($name, $value, $displayRules, $validationRules);
        $this->type = 'hidden';
        $this->displayRules['wrapAttributes']['no-wrap'] = true;
        return $this;
    }

}
