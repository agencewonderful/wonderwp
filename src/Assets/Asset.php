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
     * Asset constructor.
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param string $ver
     * @param bool $inFooter
     * @param string $groupName
     * @param array $args
     */
    public function __construct($handle='',$src='',$deps=array(),$ver='',$inFooter=false,$groupName='app',$args=array()) {
        parent::__construct();

        if($inFooter){
            $this->add_data('group', 1);
        }
        $this->concatGroup = (!empty($groupName) && is_string($groupName)) ? $groupName : 'default';
        if(!empty($args)){ $this->args = $args; }
        return $this;
    }
}

?>