<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 29/09/2016
 * Time: 16:49
 */

namespace WonderWp\Forms\Fields;


class CategoryRadioField extends RadioField
{

    public function __construct($name, $value=null, $displayRules=array(), $validationRules=array(),$parent=0)
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->setCatOptions($parent)->generateRadios();
    }

    public function setCatOptions($parent){
        $options = array();

        $args = [
            'child_of'=>$parent,
            'hide_empty'=>false
        ];
        $cats = get_categories($args);

        if(!empty($cats)){
            foreach($cats as $cat){
                /** @var $cat \WP_Term */
                $options[$cat->term_id] = __('term_'.$cat->slug,WWP_THEME_TEXTDOMAIN);
            }
        }

        $this->setOptions($options);
        return $this;
    }

}
