<?php

namespace WonderWp\Framework\Templates\Views;

class VueFrag {

    private $templateFile;
    private $values;

    public function __construct($templateFile,$values=array())
    {
        $this->templateFile = $templateFile;
        $this->values = $values;
    }

    public function render($values = array()){
        $params = \WonderWp\array_merge_recursive_distinct($this->values,$values);
        //Spread attributes
        if(!empty($params)){ foreach($params as $key=>$val){
            $$key = $val;
        }}
        include $this->templateFile;
    }

}
