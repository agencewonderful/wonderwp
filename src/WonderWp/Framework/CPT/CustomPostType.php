<?php

namespace WonderWp\Framework\CPT;

use function WonderWp\Framework\array_merge_recursive_distinct;

class CustomPostType
{
    /** @var string */
    protected $name;
    /** @var array */
    protected $opts;
    /** @var string */
    protected $taxonomy_name;
    /** @var array */
    protected $taxonomy_opts;

    public function __construct($name = '', array $passed_opts = [], $taxonomy_name = '', array $passed_taxonomy_opts = [])
    {
        $defaultOpts         = static::getDefaultOpts();
        $defaultTaxonomyOpts = static::getDefaultTaxonomyOpts();
        $this->name          = !empty($name) ? $name : static::getDefaultName();
        $this->opts          = array_merge_recursive_distinct($defaultOpts, $passed_opts);
        $this->taxonomy_name = !empty($taxonomy_name) ? $taxonomy_name : static::getDefaultTaxonomyName();
        $this->taxonomy_opts = array_merge_recursive_distinct($defaultTaxonomyOpts, $passed_taxonomy_opts);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getOpts()
    {
        return $this->opts;
    }

    /**
     * @param array $opts
     *
     * @return static
     */
    public function setOpts($opts)
    {
        $this->opts = $opts;

        return $this;
    }

    /**
     * @return string
     */
    public function getTaxonomyName()
    {
        return $this->taxonomy_name;
    }

    /**
     * @param string $taxonomy_name
     *
     * @return static
     */
    public function setTaxonomyName($taxonomy_name)
    {
        $this->taxonomy_name = $taxonomy_name;

        return $this;
    }

    /**
     * @return array
     */
    public function getTaxonomyOpts()
    {
        return $this->taxonomy_opts;
    }

    /**
     * @param array $taxonomy_opts
     *
     * @return static
     */
    public function setTaxonomyOpts($taxonomy_opts)
    {
        $this->taxonomy_opts = $taxonomy_opts;

        return $this;
    }

    public static function getDefaultName()
    {
        return '';
    }

    public static function getDefaultOpts()
    {
        return [
            'public'              => true,
            'hierarchical'        => false,
            'show_in_admin_bar'   => false,
            'exclude_from_search' => true,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        ];
    }

    public static function getDefaultTaxonomyName()
    {
        return '';
    }

    public static function getDefaultTaxonomyOpts()
    {
        return [];
    }

    public function register()
    {
        if (!empty($this->getName())) {
            $this->registerCustomPostType();
        }
        if (!empty($this->getTaxonomyName())) {
            $this->registerCustomPostTypeTaxonomy();
        }
    }

    protected function registerCustomPostType()
    {
        register_post_type($this->getName(), $this->getOpts());
    }

    protected function registerCustomPostTypeTaxonomy()
    {
        register_taxonomy($this->getTaxonomyName(), [$this->getName()], $this->getTaxonomyOpts());
    }

}
