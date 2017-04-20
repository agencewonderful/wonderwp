<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 21:23
 */

namespace WonderWp\Framework\Search;

use WonderWp\Framework\DependencyInjection\Container;

abstract class AbstractSearchEngine implements SearchEngineInterface
{
    /**
     * @var SearchServiceInterface[]
     */
    protected $services = [];

    /**
     * @var array
     */
    protected $results = [];

    /** @inheritdoc*/
    public function getServices()
    {
        return $this->services;
    }

    /** @inheritdoc*/
    public function setServices(array $services)
    {
        foreach ($services as $service) {
            $this->addService($service);
        }

        return $this;
    }

    /** @inheritdoc*/
    public function addService(SearchServiceInterface $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /** @inheritdoc*/
    public function renderResults($query, array $opts = [], array $servicesNames = [])
    {
        if (!empty($this->services)) {
            foreach ($this->services as $searchService) {
                if (0 === count($servicesNames) || in_array($searchService->getName(), $servicesNames)) {
                    $this->results[] = $searchService->getMarkup($query, $opts);
                }
            }
        }

        return implode('', $this->results);
    }
}
