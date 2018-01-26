<?php

namespace WonderWp\Framework\Search\Engine;

use WonderWp\Framework\Search\Service\SearchServiceInterface;

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
        $this->services[$service->getName()] = $service;

        return $this;
    }

    /** @inheritdoc*/
    public function renderResults($query, array $opts = [], array $servicesNames = [])
    {

        if (!empty($this->services)) {
            if (count($servicesNames) > 0) {
                foreach ($servicesNames as $serviceName) {
                    foreach ($this->services as $searchService) {
                        if ($serviceName === $searchService->getName()) {
                            $this->results[] = $searchService->getMarkup($query, $opts);
                            break;
                        }
                    }
                }
            } else {
                foreach ($this->services as $searchService) {
                    $this->results[] = $searchService->getMarkup($query, $opts);
                }
            }
        }

        return implode('', $this->results);
    }
}
