<?php

namespace WonderWp\Assets;
use WonderWp\DI\Container;

/**
 * AssetsManager
 *
 * @author Jeremy D Noe
 */
class AssetManager {

    /**
     * Instance
     * @var AssetsManager
     */
    private static $_instance;
    /**
     * Array $_dependencies, the name/path association for each javascript file
     * @var array
     */
    protected $_dependencies = array();
    /**
     * Array $_queue, internal queue used when processing dependencies
     * @var array
     */
    protected $_queue = array();

    protected $_services = array();


    /**
     * Prevent external instance creation
     */
    private function __construct () {
        $this->_dependencies=array(
            'js'=>array(),
            'css'=>array()
        );
        $this->_queue=array(
            'js'=>array(),
            'css'=>array()
        );
    }

    /**
     * Prevent external instance cloning
     */
    private function __clone () {}

    /**
     * Get singleton instance
     * @return AssetsManager
     */
    public static function getInstance () {
        $called = \get_called_class();
        if (!(self::$_instance instanceof $called)){
            $instance = new $called();
            self::$_instance = $instance;
        }
        return self::$_instance;
    }

    public function addAssetService(AssetServiceInterface $assetService){
        $this->_services[] = $assetService;
    }

    public function callServices(){
        if(!empty($this->_services)){ foreach($this->_services as $service){
            /** @var AssetServiceInterface $service */
            $assets = $service->getAssets();
            if(!empty($assets['css'])){ foreach($assets['css'] as $cssAsset){
                $this->registerAsset('css',$cssAsset);
            }}
            if(!empty($assets['js'])){ foreach($assets['js'] as $jsAsset){
                $this->registerAsset('js',$jsAsset);
            }}
        }}
    }

    /**
     * Add a dependency to consider
     * @param string $type ('js' || 'css'), the type of asset to use
     * @param Asset $dependency
     * @example
     * <code>
     * $assetsManager = AssetsManager::getInstance();
     * $assetsManager->registerAsset('js',new Asset('jquery','https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js',null,null,1,0));
     * $assetsManager->registerAsset('js',new Asset('flexsliderCore',WP_THEME_URL.'/js/registered/flexslider/jquery.flexslider-min.js',array('jquery'),null,1,1));
     * </code>
     * @return AssetsManager
     */
    public function registerAsset($type,$dependency){
        $dependencies = $this->_dependencies;
        $dependencies[$type][$dependency->handle] = $dependency;
        $this->_dependencies = $dependencies;
        return $this;
    }

    /**
     * add requires scripts or groups to a script or stylesheet already there
     * @param string $type js or css
     * @param string $script
     * @param array $requires
     */
    public function addRequires($type, $script, $requires) {
        if(!is_array($requires)) $requires = array($requires);
        if($this->_dependencies[$type][$script]) {
            $this->_dependencies[$type][$script]->requires = array_merge($this->_dependencies[$type][$script]->requires, $requires);
        }
    }

    /**
     * Get the array of dependencies (from a particular type if specififed)
     * @param string $type ('js' || 'css'), the type of asset to use
     * @return array
     */
    public function getDependencies($type=''){
        if(in_array($type, array('js','css'))){
            return $this->_dependencies[$type];
        } else {
            return array();
        }
    }

    /**
     * Get a specific dependency
     * @param string $type ('js' || 'css'), the type of asset to use
     * @param string $name, the dependency name
     * @return Asset $dep
     */
    public function getDependency($type,$name){
        $dep = !empty($this->_dependencies[$type][$name]) ? $this->_dependencies[$type][$name] : null;
        return $dep;
    }

    /**
     * Return the array of dependencies in the right order
     * @param array $toRender
     * @param string $type, the type to get (by default js)
     * @param array $groups array of groups to load
     * @return array
     */
    public function getFlatDependencies($toRender=array(),$type='js', $groups = false){

        if($groups) {
            $filesToRender = array();
            foreach($groups as $group) {
                $groupFiles = $this->getDependenciesFromGroup($group);
                foreach($groupFiles as $dep) {
                    /* @var $dep Asset */
                    if($group != 'min' || in_array($dep->handle, $toRender)) $filesToRender[] = $dep->handle;
                }
            }
            $toRender = array_merge($toRender, $filesToRender);
        }

        $this->orderDependencies($toRender,$type);
        $this->disambiguateDependencies($type);
        $jsIndex = $this->_dependencies[$type];
        $fullQueue = array();
        $groupsOrder = array();
        if(!empty($this->_queue[$type])){ foreach($this->_queue[$type] as $i=>$handle){
            /* @var $handle Asset */
            if(!empty($jsIndex[$handle])){
                $group = $jsIndex[$handle]->concatGroup;
                if(!isset($fullQueue[$group])) $fullQueue[$group] = array();
                $fullQueue[$group][]=$jsIndex[$handle];
                $groupsOrder[$group] = $i;
            }
        }}
        $fullQueueOrdered = array();
        asort($groupsOrder);
        foreach($groupsOrder as $group => $i) {
            $fullQueueOrdered[$group] = $fullQueue[$group];
        }
        $this->_queue[$type] = $fullQueueOrdered;
        return $this->_queue[$type];
    }

    /**
     * Reorder the dependencies in the right order
     * @param array $toRender
     * @param string $type, the type to reorder (by default js)
     * @return array
     */
    public function orderDependencies($toRender=array(),$type='js'){
        $jsIndex = $this->_dependencies[$type];
        if(!empty($toRender)){ foreach($toRender as $handle){
            /* @var $s Asset */
            if(!empty($jsIndex[$handle])){
                $s = $jsIndex[$handle];
                $deps = $s->deps;
                if(!empty($deps)){ $this->orderDependencies($deps,$type,false); }
                //echo'<br />Ordering '.$s->name;
                array_push($this->_queue[$type],$s->handle);
            } else if(strpos($handle, 'group:') !== false) {
                $group = str_replace('group:', '', $handle);
                $deps = $this->getDependenciesFromGroup($group, $type);
                $depsFlat = array();
                foreach($deps as $dep) {
                    $depsFlat[] = $dep->handle;
                }
                $this->orderDependencies($depsFlat, $type);
            }
        }}
        return $this->_queue[$type];
    }

    /**
     * Remove doublons from the dependencies queue
     * @param string $type, the type to disambiguate (by default js)
     * @return array
     */
    public function disambiguateDependencies($type='js'){
        $this->_queue[$type] = array_unique($this->_queue[$type]);
        return $this->_queue[$type];
    }

    /**
     * get all dependencies from a specific group
     * @param string $group
     * @param string $type (js || css)
     * @return array $return
     */
    public function getDependenciesFromGroup($group, $type = 'js') {
        $deps = $this->_dependencies[$type];
        $return = array();
        foreach($deps as $dep) {
            /* @var $dep Asset */
            if($dep->group == $group) $return[] = $dep;
        }
        return $return;
    }

}