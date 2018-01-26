<?php

namespace WonderWp\Framework\Search\Service;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Search\Renderer\SearchResultSetsRenderer;
use WonderWp\Framework\Search\Result\SearchResultInterface;
use WonderWp\Framework\Search\ResultSet\SearchResultSetInterface;

abstract class AbstractSetSearchService extends AbstractSearchService
{

    /** @inheritdoc */
    function getMarkup($query, array $opts = [])
    {
        //echo '<br />This is <strong>getMarkup</strong> in <strong>AbstractSetSearchService | ' . get_called_class() . '</strong>';
        $resultSet = $this->fetchResultSet($query, $opts);
        $markup    = $this->renderResultSet($resultSet, $query, $opts);

        return $markup;
    }

    /**
     * Get result set for a given query and options.
     *
     * @param  string $query
     * @param  array  $opts
     *
     * @return SearchResultSetInterface
     */
    protected function fetchResultSet($query, array $opts = [])
    {
        $container = Container::getInstance();

        /** @var SearchResultSetInterface $set */
        $set = $container['wwp.search.set'];
        $set->setName($this->giveSetName());
        $set->setLabel($this->giveSetLabel());
        $set->setTotalCount($this->giveSetTotalCount($query, $opts));
        if ($set->getTotalCount() > 0) {
            $set->setCollection($this->giveSetResults($query, $opts));
        }

        return $set;
    }

    /**
     * Method responsible for rendering the particular SearchResultSetInterface markup
     *
     * @param SearchResultSetInterface $resultSet
     * @param string                   $query
     * @param array                    $opts
     * @param object                   $renderer
     *
     * @return string
     */
    protected function renderResultSet(SearchResultSetInterface $resultSet, $query, array $opts = [], $renderer = null)
    {
        if ($renderer === null) {
            $container = Container::getInstance();
            /** @var SearchResultSetsRenderer $renderer */
            $renderer = $container['wwp.search.renderer'];
        }

        $opts['search_service'] = $this->getName();

        return $renderer->getMarkup([$resultSet], $query, $opts);
    }

    /**
     * @return string
     */
    abstract protected function giveSetName();

    /**
     * @return string
     */
    abstract protected function giveSetLabel();

    /**
     * @param string $query
     * @param array  $opts
     *
     * @return int
     */
    abstract protected function giveSetTotalCount($query, array $opts = []);

    /**
     * @param string $query
     * @param array  $opts
     *
     * @return SearchResultInterface[]
     */
    abstract protected function giveSetResults($query, array $opts = []);

}
