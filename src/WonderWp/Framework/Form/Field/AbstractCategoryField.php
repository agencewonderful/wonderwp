<?php

namespace WonderWp\Framework\Form\Field;

use WonderWp\Framework\Form\Field\SelectField;
use WonderWp\Framework\Form\Field\OptionsFieldInterface;

abstract class AbstractCategoryField extends SelectField
{

    /** @var array **/
    protected $categories;
    /** @var int **/
    protected $parentCategory;
    /** @var array **/
    protected $selectOptions;

    /**
     * @param string $name
     * @param int    $defaultValue
     * @param array  $displayRules
     * @param array  $validationRules
     * @param int    $parentCategory
     */
    public function __construct(
        $name,
        $defaultValue = null,
        array $displayRules = [],
        array $validationRules = [],
        $parentCategory = null
    ) {
        parent::__construct($name, $defaultValue, $displayRules, $validationRules);

        // warning : temporal coupling
        $this->parentCategory = $parentCategory;
        $this->categories     = $this->getCategories();
        $this->selectOptions  = $this->getDefaultOption();

        $this->doSetOptions();
    }

    /**
     * Populates the options list
     * @return OptionsFieldInterface
     */
    abstract protected function doSetOptions();

    /**
     * Returns the child categories (see the parent one in the constructor)
     * @return array
     */
    private function getCategories()
    {
        return get_categories([
            'child_of'   => $this->parentCategory,
            'hide_empty' => false
        ]);
    }

    /**
     * Returns the first option
     * @return string
     */
    protected function getFirstOption()
    {
        $displayRules = $this->getDisplayRules();

        if (isset($displayRules['firstOptionLabel'])) {
            return $displayRules['firstOptionLabel'];
        } elseif (isset($displayRules['label'])) {
            return $displayRules['label'];
        } else {
            return '';
        }
    }

    /**
     * Returns the starting default options list
     * return array
     */
    private function getDefaultOption()
    {
        return [
            '' => $this->getFirstOption()
        ];
    }

}
