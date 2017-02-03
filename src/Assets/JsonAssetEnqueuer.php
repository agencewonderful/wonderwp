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
    protected $version;

    public function __construct($manifestPath)
    {
        parent::__construct();
        $this->_manifest = json_decode(file_get_contents($manifestPath));
        $this->_blogurl = rtrim("//{$_SERVER['HTTP_HOST']}",'/');
        $this->version = 0;
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
                if($env=='production' || $env=='staging') {
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

    public function enqueueCritical($groupNames)
    {
        $versionNum = $this->getVersion();
        if(!empty($groupNames)) {
            foreach ($groupNames as $group) {
                if (array_key_exists($group, $this->_manifest->js)) {
                    $src = $_SERVER['DOCUMENT_ROOT'].str_replace($this->_container['wwp.assets.folder.prefix'], '', $this->_manifest->site->assets_dest) . '/js/' . $group . $versionNum . '.js';
                    if(file_exists($src)){
                        $content = file_get_contents($src);
                        if(!empty($content)){
                            echo'<script id="critical-js">
                                '.$content.'
                            </script>';
                        }
                    }
                }

                if (array_key_exists($group, $this->_manifest->css)) {
                    $src = $_SERVER['DOCUMENT_ROOT'] . str_replace($this->_container['wwp.assets.folder.prefix'], '', $this->_manifest->site->assets_dest) . '/css/' . $group . $versionNum . '.css';
                    if(file_exists($src)){
                        $content = file_get_contents($src);
                        if(!empty($content)){
                            echo'<style id="critical-css">
                                '.$content.'
                            </style>';
                        }
                    }
                }
            }
        }
    }

    public function getVersion(){
        if(empty($this->version) && $this->_container->offsetExists('wwp.assets.folder.dest')) {
            $fileVersion = $_SERVER['DOCUMENT_ROOT'] .'/'. $this->_container['wwp.assets.folder.dest'] . '/version.php';
            $this->version = file_exists($fileVersion) ? include($fileVersion) : null;
        }
        return $this->version;
    }

}