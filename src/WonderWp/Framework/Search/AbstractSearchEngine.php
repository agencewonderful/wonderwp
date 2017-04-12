<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 21:23
 */

namespace WonderWp\Framework\Search;

abstract class AbstractSearchEngine implements SearchEngineInterface
{
    /**
     * @var SearchServiceInterface[]
     */
    protected $services = [];

    /**
     * @var SearchResultSetInterface[]
     */
    protected $resultsSet = [];

    /** @inheritdoc*/
    public function getServices()
    {
        return $this->services;
    }

    /** @inheritdoc*/
    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    /** @inheritdoc*/
    public function getResultsSet()
    {
        return $this->resultsSet;
    }

    /** @inheritdoc*/
    public function setResultsSet($resultsSet)
    {
        $this->resultsSet = $resultsSet;

        return $this;
    }

    /** @inheritdoc*/
    public function addService(SearchServiceInterface $service)
    {
        $this->services[] = $service;

        return $this;
    }

}
