<?php

namespace WonderWp\Templates\Views;

class AdminVue {
    
    protected $_frags = array();

    /**
     * @param vueFrag $frag
     * @return $this
     */
    public function addFrag(vueFrag $frag){
        $this->_frags[] = $frag;
        return $this;
    }

    /**
     * @param string $prefix
     * @param array $frags
     * @return $this
     */
    public function registerFrags($prefix,$frags=array()){
        if(!empty($frags)){ foreach($frags as $vueFrag){
            $this->addFrag($vueFrag);
        }}
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function render($params = array()){
        if(!empty($this->_frags)){ foreach($this->_frags as $frag){
            /** @var VueFrag $frag */
            $frag->render($params);
        }}
        return $this;
    }

}