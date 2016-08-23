<?php

namespace WonderWp\Assets;

/**
 * Asset
 *
 * @author Jeremy D Noe
 */
class Asset extends \_WP_Dependency{

    /**
     * Group of this dependency, use GROUP_ consts
     * @var string
     */
    public $concatGroup;

    /**
     * Constructor
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param string $ver
     * @param bool $inFooter
     * @param string $group
     * @param array $args
     * @return AssetsDependency
     */
    public function __construct($handle='',$src='',$deps=array(),$ver='',$inFooter=false,$groupName='app',$args=array()) {
        if(!empty($handle)){ $this->handle = $handle; }
        if(!empty($src)){ $this->src = $src; }
        if(!empty($deps) && is_array($deps)){ $this->deps = $deps; } else { $this->deps=array(); }
        if(!empty($ver)){ $this->ver = $ver; }

        if($inFooter){
            $this->add_data('group', 1);
        }
        $this->concatGroup = (!empty($groupName) && is_string($groupName)) ? $groupName : 'default';
        if(!empty($args)){ $this->args = $args; }
        return $this;
    }
}

?>