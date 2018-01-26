<?php

namespace WonderWp\Framework\Search\Engine;

use WonderWp\Framework\Search\Service\SearchServiceInterface;

/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:04
 */
interface SearchEngineInterface
{
    /**
     * Get search service.
     *
     * @return SearchServiceInterface[]
     */
    public function getServices();

    /**
     * Set search services.
     *
     * @param  array $services
     *
     * @return static
     */
    public function setServices(array $services);

    /**
     * Add a search service.
     *
     * @param SearchServiceInterface $service
     *
     * @return static
     */
    public function addService(SearchServiceInterface $service);

    /**
     * Render results for a given query.
     *
     * @param  string $query
     * @param  array  $opts
     * @param  array  $servicesNames
     *
     * @return string
     */
    public function renderResults($query, array $opts = [], array $servicesNames = []);
}
