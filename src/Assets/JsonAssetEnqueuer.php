<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 17/08/2016
 * Time: 12:39
 */

namespace WonderWp\Assets;

class JsonAssetEnqueuer extends AbstractAssetEnqueuer{

    protected $_manifest;
    protected $_blogurl;

    public function __construct($manifestPath)
    {
        parent::__construct();
        $this->_manifest = json_decode(file_get_contents($manifestPath));
        $this->_blogurl = get_bloginfo('url');
    }

    public function enqueueStyles($groupNames)
    {
        $versionNum = $this->getVersion();

        if(!empty($groupNames)){ foreach($groupNames as $group){
            if(array_key_exists($group,$this->_manifest->css)) {
                $src = $this->_blogurl . str_replace($this->_container['wwp.assets.folder.prefix'], '', $this->_manifest->site->assets_dest) . '/css/' . $group . $versionNum . '.css';
                wp_enqueue_style($group, $src, array(), null);
            }
        }}
    }

    public function enqueueScripts($groupNames)
    {
        $env = env('WP_ENV');
        $versionNum = $this->getVersion();

        if(!empty($groupNames)){ foreach($groupNames as $group){
            if(array_key_exists($group,$this->_manifest->js)) {
                if($env=='production' || $env=='preprod') {
                    $src = $this->_blogurl . str_replace($this->_container['wwp.assets.folder.prefix'], '', $this->_manifest->site->assets_dest) . '/js/' . $group . $versionNum . '.js';
                    wp_enqueue_script($group, $src, array(), null, true);
                } else {
                    if(!empty($this->_manifest->js->$group)){ foreach($this->_manifest->js->$group as $i=>$jsFile){
                        $src = $this->_blogurl . str_replace($this->_container['wwp.assets.folder.prefix'], '', $jsFile);
                        $handle = $group.'_'.$i;
                        wp_enqueue_script($handle, $src, array(), null, true);
                    }}
                }
            }
        }}
    }

    public function getVersion(){
        $fileVersion = $_SERVER['DOCUMENT_ROOT'].$this->_container['wwp.assets.folder.dest'].'/version.php';
        return file_exists($fileVersion) ? include($fileVersion) : null;
    }

}