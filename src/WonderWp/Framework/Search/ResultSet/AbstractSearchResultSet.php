<?php

namespace WonderWp\Framework\Search\ResultSet;

use WonderWp\Framework\Search\Result\SearchResultInterface;

abstract class AbstractSearchResultSet implements SearchResultSetInterface
{
    /** @var  string */
    protected $name;

    /** @var  string */
    protected $label;

    /** @var  SearchResultInterface[] */
    protected $collection = [];

    /** @var  int */
    protected $totalCount;

    /** @inheritdoc */
    public function getName()
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /** @inheritdoc */
    public function getLabel()
    {
        return $this->label;
    }

    /** @inheritdoc */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /** @inheritdoc */
    public function getCollection()
    {
        return $this->collection;
    }

    /** @inheritdoc */
    public function setCollection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /** @inheritdoc */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /** @inheritdoc */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;

        return $this;
    }

}
