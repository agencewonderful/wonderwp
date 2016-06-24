<?php

namespace WonderWp\Templates;

class AdminVue {
    
    private $template = array();
    
    public function addFrag(vueFrag $frag){
        $this->template[] = $frag;
    }

    public function render(){
        if(!empty($this->template)){ foreach($this->template as $frag){
            $frag->render();
        }}
    }

}