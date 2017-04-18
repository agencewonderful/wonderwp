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
    public function addService(SearchServiceInterface $service)
    {
        $this->services[] = $service;

        return $this;
    }

    public function renderResults($results)
    {
        /** @var SearchResultsRendererInterface $renderer */
        $renderer = Container::getInstance()->offsetGet('wwp.search.renderer');
        echo $renderer->getMarkup($results, []);

    }

}
