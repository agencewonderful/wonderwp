<?php

namespace WonderWp\Framework\AbstractPlugin;

abstract class AbstractPluginActivator implements ActivatorInterface
{
    /** @var string */
    protected $version;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /** @inheritdoc */
    public function activate()
    {
    }

    /**
     * @param string $srcLangFolder
     */
    protected function copyLanguageFiles($srcLangFolder)
    {
        $destLangFolder = WP_LANG_DIR . DIRECTORY_SEPARATOR . 'plugins';
        if (is_dir($srcLangFolder)) {
            $langFiles = glob($srcLangFolder . '/*');
            $copied    = [];
            if (!empty($langFiles)) {
                foreach ($langFiles as $langFile) {
                    if (!file_exists($destLangFolder . DIRECTORY_SEPARATOR . basename($langFile))) {
                        $copied[] = copy($langFile, $destLangFolder . DIRECTORY_SEPARATOR . basename($langFile));
                    }
                }
            }
        }
    }
}
