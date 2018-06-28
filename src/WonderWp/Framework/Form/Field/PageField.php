<?php

namespace WonderWp\Framework\Form\Field;

class PageField extends SelectField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, array $displayRules = [], array $validationRules = [], array $args = [])
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->setPageOptions($args);
    }

    /**
     * @param array $args
     *
     * @return static
     */
    public function setPageOptions(array $args)
    {
        $defaults = [
            'depth'                 => 0,
            'child_of'              => 0,
            'selected'              => 0,
            'echo'                  => 0,
            'name'                  => 'page_id',
            'id'                    => '',
            'class'                 => '',
            'show_option_none'      => '',
            'show_option_no_change' => '',
            'option_none_value'     => '',
            'value_field'           => 'ID',
            'sort_column'           => 'menu_order',
        ];
        $r        = wp_parse_args($args, $defaults);

        $options = [
            0 => __('Page'),
        ];

        $pages = get_pages($r);

        foreach ($pages as $page) {
            /** @var $page \WP_Post */
            $options[$page->ID] = $page->post_title;
        }

        $this->setOptions($options);

        return $this;
    }
}
