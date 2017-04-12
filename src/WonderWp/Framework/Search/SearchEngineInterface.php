<?php

namespace WonderWp\Framework\Search;

/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:04
 */
interface SearchEngineInterface
{

    /**
     * @return SearchServiceInterface[]
     */
    public function getServices();

    /**
     * @param SearchServiceInterface[] $services
     *
     * @return static
     */
    public function setServices($services);

    /**
     * @return SearchResultSetInterface[]
     */
    public function getResultsSet();

    /**
     * @param SearchResultSetInterface[] $resultsSet
     *
     * @return static
     */
    public function setResultsSet($resultsSet);

    /**
     * @param SearchServiceInterface $service
     *
     * @return static
     */
    public function addService(SearchServiceInterface $service);

    /**
     * @param       $query
     * @param array $opts
     *
     * @return SearchResultSet[]
     */
    public function getResultSets($query, array $opts = []);

}
