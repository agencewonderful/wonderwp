<?php

namespace WonderWp\Framework\Form\Field;

use WonderWp\Framework\Form\Field\AbstractCategoryField;

class CategoryField extends AbstractCategoryField
{

    /**
     * @inheritDoc
     */
    public function doSetOptions()
    {
        foreach ($this->categories as $category) {
            /** @var \WP_Term $category */
            $this->selectOptions[$category->term_id] = $category->name;
        }

        $this->setOptions($this->selectOptions);

        return $this;
    }

}
