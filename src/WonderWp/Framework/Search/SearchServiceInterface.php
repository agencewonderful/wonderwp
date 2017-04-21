<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:08
 */

namespace WonderWp\Framework\Search;

interface SearchServiceInterface
{
    /**
     * Set search service name.
     *
     * @param  string $name
     *
     * @return static
     */
    public function setName($name);

    /**
     * Return search service name.
     *
     * @return string
     */
    public function getName();

    /**
     * Return results as html markup for a given query and opts.
     *
     * @param  string $query
     * @param  array  $opts
     *
     * @return string
     */
    public function getMarkup($query, array $opts = []);
}
