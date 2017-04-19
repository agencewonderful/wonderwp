<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 18/04/2017
 * Time: 16:29
 */

namespace WonderWp\Framework\Search;

use WonderWp\Framework\DependencyInjection\Container;

class SetSearchEngine extends AbstractSearchEngine
{

    /**
     * @var SearchResultSetInterface[]
     */
    protected $resultsSet = [];

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

    public function getResults($query, array $opts = [])
    {
        if (!empty($this->services)) {
            foreach ($this->services as $searchService) {
                $this->resultsSet[] = $searchService->getResultSet($query, $opts);
            }
        }
        return $this->getResultsSet();
    }
}
