<?php

namespace WonderWp\Framework\Template\Views;

class VueFrag
{
    /** @var string */
    protected $templateFile;
    /** @var array */
    protected $values;

    /**
     * @param string $templateFile
     * @param array  $values
     */
    public function __construct($templateFile, array $values = [])
    {
        $this->templateFile = $templateFile;
        $this->values       = $values;
    }

    /**
     * @param array $values
     */
    public function render(array $values = [])
    {
        $params = \WonderWp\Framework\array_merge_recursive_distinct($this->values, $values);
        // Spread attributes
        extract($params);

        include $this->templateFile;
    }
}
