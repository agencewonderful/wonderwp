<?php

namespace WonderWp\Assets;

/**
 * Asset
 *
 * @author Jeremy D Noe
 */
class Asset{
    /**
     * The name of the dependency
     * @var string
     */
    public $name;
    /**
     * The location of the dependency
     * @var string
     */
    public $source;
    /**
     * The array of dependencies names
     * @var array
     */
    public $requires;
    /**
     * The dependency version number
     * @var string
     */
    public $version;
    /**
     * Do I load the dependency in the footer?
     * @var bool
     */
    public $inFooter;
    /**
     * Group of this dependency, use GROUP_ consts
     * @var string
     */
    public $group;
    /**
     * Other kind of arguments
     * @var array
     */
    public $args;
    /**
     * render js of all group or just this file
     * @var boolean
     */
    public $renderAllGroup;

    /**
     * Constructor
     * @param string $name
     * @param string $source
     * @param array $requires
     * @param string $version
     * @param bool $inFooter
     * @param string $group
     * @param array $args
     * @return AssetsDependency
     */
    public function __construct($name='',$source='',$requires=array(),$version='',$inFooter=false,$group='default',$args=array()) {
        if(!empty($name)){ $this->name = $name; }
        if(!empty($source)){ $this->source = $source; }
        if(!empty($requires) && is_array($requires)){ $this->requires = $requires; } else { $this->requires=array(); }
        if(!empty($version)){ $this->version = $version; }
        if(!empty($inFooter)){ $this->inFooter = $inFooter; }
        $this->group = (!empty($group) && is_string($group)) ? $group : 'default';
        if(!empty($args)){ $this->args = $args; }
        return $this;
    }
}

?>