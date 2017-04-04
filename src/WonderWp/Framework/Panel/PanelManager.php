<?php

namespace WonderWp\Framework\Panel;

use WonderWp\DI\Container;
use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Form;
use WonderWp\HttpFoundation\Request;

/**
 * Class NoewpPageBOParameterPanelManager
 * @package NoewpModels
 * @since   08/07/2011
 */
class PanelManager
{

    /**
     * The list of panels
     * @var array
     */
    private $_panelList = [];

    /**
     * Ajout d'un panneau d'administration à la page
     *
     * @param PanelInterface $panel
     *
     * @return $this
     */
    public function registerPanel(PanelInterface $panel)
    {
        //Ajout du panneau courant dans la liste du manager
        $panelList = $this->_panelList;
        $id        = $panel->getId();

        /** @var PanelInterface $panel */
        $postTypes = $panel->getPostTypes();

        if (!empty($postTypes)) {
            foreach ($postTypes as $key => $postType) {
                add_meta_box($panel->getId() . '_custombox', $panel->getTitle(), [&$this, 'displayPanel'], $postType);
            }
        }

        $panelList[$id]   = $panel;
        $this->_panelList = $panelList;

        return $this;
    }

    /**
     * @param $panelId
     *
     * @return PanelInterface|null
     */
    public function getPanel($panelId)
    {
        return isset($this->_panelList[$panelId]) ? $this->_panelList[$panelId] : null;
    }

    /**
     * Affiche le contenu du panneau dans le panneau
     * @since 08/07/2011
     *
     * @param object $post    , le post en cours
     * @param array  $context , toutes les données utiles
     */
    public function displayPanel(\WP_Post $post, $context)
    {
        $container = Container::getInstance();

        $panelid = str_replace('_custombox', '', $context['id']);
        $panel   = $this->getPanel($panelid);

        if ($panel instanceof PanelInterface) {
            $fields = $panel->getFields();
            if (!empty($fields)) {
                //On recupere les parametres et leur données sauvegardées
                $savedData = get_post_meta($post->ID, $panelid, true);
                if (is_serialized($savedData)) {
                    $savedData = unserialize($savedData);
                }

                /** @var Form $form */
                $form = $container->offsetGet('wwp.forms.form');
                foreach ($fields as $f) {
                    /** @var AbstractField $f */
                    $fname = $f->getName();

                    $value = !empty($savedData[$fname]) ? $savedData[$fname] : null;
                    if ($value == 'on') {
                        $value = 1;
                    } elseif (is_serialized($value)) {
                        $value = unserialize($value);
                    }
                    if ($value !== null) {
                        $f->setValue($value);
                    }

                    $form->addField($f);
                }
                $opts = [
                    'formStart' => [
                        'showFormTag' => 0,
                    ],
                    'formEnd'   => [
                        'showSubmit' => 0,
                    ],
                ];
                echo $form->renderView($opts);
            }
        }
    }

    /**
     * Sauvegarde les informations du panneau
     * @since 08/07/2011
     * @return int $post_id
     */
    public function savePanels()
    {
        //Verifs de securite
        $request  = Request::getInstance();
        $post_id  = $request->get('post_ID', 0);
        $postType = $request->request->get('post_type');
        //$nonce = $request->get('module_noncename', ''); // verify this came from the our screen and with proper authorization, because save_post can be triggered at other times
        /*if (!wp_verify_nonce($nonce, plugin_basename(__FILE__))) {
            echo 1; die;
            return $post_id;
        }*/
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
        if (!empty($postType) && 'page' == $postType) {// Check permissions
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        }

        // OK, we're authenticated: we need to find and save the data
        $panelList = $this->_panelList;
        if (!empty($panelList)) {
            foreach ($panelList as $panel) {
                /** @var PanelInterface $panel */

                $metakey = $panel->getId();
                $metaval = [];

                //Suppression de l'ancienne valeur
                delete_post_meta($post_id, $metakey);

                $fields = $panel->getFields();
                if (!empty($fields)) {
                    foreach ($fields as $f) {
                        /** @var $f AbstractField */
                        if (!empty($f->getName())) {
                            $key = $f->getName();
                            delete_post_meta($post_id, $metakey . $key);

                            if (!empty($request->request->get($key))) {
                                $val = $request->request->get($key);
                                if (is_array($val) || is_object($val)) {
                                    $val = serialize($val);
                                }
                                $metaval[$key] = $val;
                                //On MaJ la valeur individuelle, utile pour faire des query avec get_posts en utilisant les champs meta_key et meta_value.
                                //La cle etant faite de $metakey.$key
                                add_post_meta($post_id, $metakey . $key, $val);
                            }
                        }
                    }
                }
                //On met à jour la valeur d'ensemble
                add_post_meta($post_id, $metakey, serialize($metaval));
            }
        }

        return $post_id;
    }

}
