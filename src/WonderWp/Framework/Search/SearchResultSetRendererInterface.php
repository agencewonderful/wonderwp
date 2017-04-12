<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:54
 */

namespace WonderWp\Framework\Search;

interface SearchResultSetRendererInterface
{
    /**
     * @return SearchResultSetInterface[]
     */
    public function getSets();

    /**
     * @param SearchResultSetInterface[] $sets
     *
     * @return static
     */
    public function setSets($sets);

    /**
     * @param array $opts
     *
     * @return mixed
     */
    public function getMarkup(array $opts = []);
}
