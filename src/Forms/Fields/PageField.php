<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 29/09/2016
 * Time: 16:49
 */

namespace WonderWp\Forms\Fields;


class PageField extends SelectField
{

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array(),$parent=0)
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->setPageOptions($parent);
    }

    public function setPageOptions($args){

        $defaults = array(
            'depth' => 0, 'child_of' => 0,
            'selected' => 0, 'echo' => 0,
            'name' => 'page_id', 'id' => '',
            'class' => '',
            'show_option_none' => '', 'show_option_no_change' => '',
            'option_none_value' => '',
            'value_field' => 'ID',
        );
        $r = wp_parse_args( $args, $defaults );

        $options = array(
            0=>__('Page')
        );

        $pages = get_pages($defaults);

        if(!empty($pages)){
            foreach($pages as $page){
                /** @var $page \WP_Term */
                $options[$page->ID] = $page->post_name;
            }
        }

        $this->setOptions($options);
        return $this;
    }

}