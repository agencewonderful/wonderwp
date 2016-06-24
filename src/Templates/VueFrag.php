<?php

namespace WonderWp\Templates;

class VueFrag {

    private $templateFile;
    private $values;

    public function __construct($templateFile,$values=array())
    {
        $this->templateFile = $templateFile;
        $this->values = $values;
    }

    public function render(){
        $values = $this->values;
        include $this->templateFile;
    }

}