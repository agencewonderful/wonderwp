<?php

namespace WonderWp\Framework\Search\Renderer;

use WonderWp\Framework\Search\ResultSet\SearchResultSetInterface;

abstract class AbstractSearchResultSetRenderer implements SearchResultSetRendererInterface
{
    /**
     * @var SearchResultSetInterface[]
     */
    protected $sets = [];

    /** @inheritdoc */
    public function getSets()
    {
        return $this->sets;
    }

    /** @inheritdoc */
    public function setSets($sets)
    {
        $this->sets = $sets;

        return $this;
    }

}
