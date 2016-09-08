<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/08/2016
 * Time: 17:11
 */
namespace WonderWp\Forms\Fields;

class MediaField extends InputField{

    /**
     * HiddenField constructor.
     * @param $name
     * @param null $value
     * @param array $displayRules
     * @param array $validationRules
     * @return HiddenField
     */
    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array()){
        parent::__construct($name, $value, $displayRules, $validationRules);
        return $this;
    }

}