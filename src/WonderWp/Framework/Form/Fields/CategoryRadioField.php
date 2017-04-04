<?php

namespace WonderWp\Framework\Form\Fields;

class CategoryRadioField extends RadioField
{
    /** @inheritdoc */
    public function __construct($name, $value = null, $displayRules = [], $validationRules = [], $parent = 0)
    {
        parent::__construct($name, $value, $displayRules, $validationRules);

        $this->setCatOptions($parent)->generateRadios();
    }

    /**
     * @param integer $parent
     *
     * @return static
     */
    public function setCatOptions($parent)
    {
        $options = [];

        $args = [
            'child_of'   => $parent,
            'hide_empty' => false,
        ];
        $cats = get_categories($args);

        if (!empty($cats)) {
            foreach ($cats as $cat) {
                /** @var $cat \WP_Term */
                $options[$cat->term_id] = __('term_' . $cat->slug, WWP_THEME_TEXTDOMAIN);
            }
        }

        $this->setOptions($options);

        return $this;
    }

}
