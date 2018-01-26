<?php

namespace WonderWp\Framework\Search\Renderer;

use WonderWp\Framework\Search\ResultSet\SearchResultSetInterface;

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
