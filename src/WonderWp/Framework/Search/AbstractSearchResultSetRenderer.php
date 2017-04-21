<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:55
 */

namespace WonderWp\Framework\Search;

abstract class AbstractSearchResultSetRenderer implements SearchResultSetRendererInterface
{
    /**
     * @var SearchResultSetInterface[]
     */
    protected $sets;

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
