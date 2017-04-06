<?php

namespace WonderWp\Framework\Hook;

use WonderWp\Framework\AbstractPlugin\AbstractManager;
use WonderWp\Framework\Service\AbstractService;

abstract class AbstractHookService extends AbstractService implements HookServiceInterface
{

    /**
     * @var AbstractManager
     */
    protected $manager;

    /**
     * Load Textdomain
     */
    public function loadTextdomain()
    {
        $domain = $this->manager->getConfig('textDomain');
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        $languageDir = $this->manager->getConfig('path.base') . '/languages/';

        // wp-content/languages/plugin-name/plugin-name-de_DE.mo
        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '-' . $locale . '.mo' );
        // wp-content/plugins/plugin-name/languages/plugin-name-de_DE.mo
        load_plugin_textdomain( $domain, FALSE, $languageDir );
    }

}
