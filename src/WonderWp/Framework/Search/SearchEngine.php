<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:07
 */

namespace WonderWp\Framework\Search;

use WonderWp\Framework\DependencyInjection\Container;

class SearchEngine extends AbstractSearchEngine
{

    protected $results;

    public function getResults($query, array $opts = [])
    {
        if (!empty($this->services)) {
            foreach ($this->services as $searchService) {
                $this->results = array_merge($this->results, $searchService->getResults($query));
            }
        }
        return $this->results;
    }

}
