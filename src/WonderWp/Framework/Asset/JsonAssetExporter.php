<?php

namespace WonderWp\Framework\Asset;

class JsonAssetExporter extends AbstractAssetExporter
{
    /** @inheritdoc */
    public function export()
    {
        /**
         * Get registered assets from manager
         */
        /** @var AssetManager $assetsManager */
        $assetsManager = $this->container['wwp.assets.manager'];
        $assetsManager->callServices();

        /**
         * Prepare common and useful metas
         */
        $blogUrl      = get_bloginfo('url');
        $assetsPrefix = $this->container['wwp.assets.folder.prefix'];
        $json         = [
            'site' => [
                'id'          => get_current_blog_id(),
                'title'       => get_bloginfo('name'),
                'url'         => $blogUrl,
                'assets_src'  => $this->container['wwp.assets.folder.src'],
                'assets_dest' => $this->container['wwp.assets.folder.dest'],
                'prefix'      => $this->container['wwp.assets.folder.prefix'],
                'env'         => WP_ENV,
            ],
            'css'  => [],
            'js'   => [],
        ];

        /**
         * Export CSS files
         */
        $dependenciesCss = $assetsManager->getDependencies('css');
        $handlesCss      = array_keys($dependenciesCss);
        $cssFiles        = $assetsManager->getFlatDependencies($handlesCss, 'css');

        $cssFilesJSON = [];
        foreach ($cssFiles as $groupName => $groupFiles) {
            $cssFilesJSON[$groupName] = [];

            foreach ($groupFiles as $css) {
                $css->src                   = str_replace($blogUrl, '', $css->src);
                $cssFilesJSON[$groupName][] = $assetsPrefix . $css->src;
            }
        }
        $json['css'] = $cssFilesJSON;

        /**
         * Export JS files
         */
        $dependenciesJs = $assetsManager->getDependencies('js');
        $handlesJs      = array_keys($dependenciesJs);
        $jsFiles        = $assetsManager->getFlatDependencies($handlesJs, 'js');

        $jsFilesJSON = [];
        foreach ($jsFiles as $groupName => $groupFiles) {
            $jsFilesJSON[$groupName] = [];

            foreach ($groupFiles as $js) {
                $js->src                   = str_replace($blogUrl, '', $js->src);
                $jsFilesJSON[$groupName][] = $assetsPrefix . $js->src;
            }
        }
        $json['js'] = $jsFilesJSON;

        /**
         * Write manifest
         */
        /** @var \WP_Filesystem_Direct $filesystem */
        $manifestPath = $this->container->offsetGet('wwp.assets.manifest.path');
        $filesystem   = $this->container->offsetGet('wwp.fileSystem');
        $written      = $filesystem->put_contents(
            $manifestPath,
            json_encode($json),
            FS_CHMOD_FILE // predefined mode settings for WP files
        );

        if ($written) {
            $res = ['code' => 200, 'data' => ['msg' => 'Json assets manifest successfully written to ' . $manifestPath]];
        } else {
            $res = ['code' => 500, 'data' => ['msg' => 'Unable to create Json assets manifest to ' . $manifestPath]];
        }

        /**
         * Respond
         */
        $this->respond($res);
    }
}
