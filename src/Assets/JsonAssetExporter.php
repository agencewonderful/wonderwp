<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 17/08/2016
 * Time: 10:35
 */

namespace WonderWp\Assets;

class JsonAssetExporter extends AbstractAssetExporter{

    public function export(){
        /**
         * Get registered assets from manager
         */
        /** @var AssetManager $assetsManager */
        $assetsManager = $this->_container->offsetGet('wwp.assets.manager');
        $assetsManager->callServices();

        /**
         * Prepare common and useful metas
         */
        $blogurl = get_bloginfo('url');
        $assetsPrefix = $this->_container['wwp.assets.folder.prefix'];
        $json = array(
            'site'=>array(
                'id'=>  get_current_blog_id(),
                'title'=>  get_bloginfo('name'),
                'url' => $blogurl,
                'assets_src'=>$this->_container['wwp.assets.folder.src'],
                'assets_dest' => $this->_container['wwp.assets.folder.dest'],
                'prefix' =>$this->_container['wwp.assets.folder.prefix'],
                'env'=>WP_ENV
            ),
            'css' => array(),
            'js' => array()
        );

        /**
         * Export CSS files
         */
        $dependenciesCss = $assetsManager->getDependencies('css');
        $handlesCss = array_keys($dependenciesCss);
        $cssFiles = $assetsManager->getFlatDependencies($handlesCss, 'css');

        $cssFilesJSON = array();
        foreach ($cssFiles as $groupName => $groupFiles) {
            $cssFilesJSON[$groupName] = array(
            );
            foreach ($groupFiles as $css) {
                $css->src = str_replace($blogurl, '', $css->src);
                $cssFilesJSON[$groupName][] = $assetsPrefix.$css->src;
            }
        }
        $json['css'] = $cssFilesJSON;

        /**
         * Export JS files
         */
        $dependenciesJs = $assetsManager->getDependencies('js');
        $handlesJs = array_keys($dependenciesJs);
        $jsFiles = $assetsManager->getFlatDependencies($handlesJs, 'js');

        $jsFilesJSON = array();
        foreach ($jsFiles as $groupName => $groupFiles) {
            $jsFilesJSON[$groupName] = array(
            );
            foreach ($groupFiles as $js) {
                $js->src = str_replace($blogurl, '', $js->src);
                $jsFilesJSON[$groupName][] = $assetsPrefix.$js->src;
            }
        }
        $json['js'] = $jsFilesJSON;

        /**
         * Write manifest
         */
        /** @var \WP_Filesystem_Direct $filesystem */
        $manifestPath = $this->_container->offsetGet('wwp.assets.manifest.path');
        $filesystem = $this->_container->offsetGet('wwp.fileSystem');
        $written = $filesystem->put_contents(
            $manifestPath,
            json_encode($json),
            FS_CHMOD_FILE // predefined mode settings for WP files
        );

        if($written){
            $res = array('code'=>200,'data'=>array('msg'=>'Json assets manifest successfully written to '.$manifestPath));
        } else {
            $res = array('code'=>500,'data'=>array('msg'=>'Unable to create Json assets manifest to '.$manifestPath));
        }

        /**
         * Respond
         */
        $this->respond($res);
    }

}