<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:07
 */

namespace WonderWp\Framework\Search;

class SearchEngine extends AbstractSearchEngine
{

    public function getResultSets($query, array $opts = [])
    {
        if (!empty($this->services)) {
            foreach ($this->services as $searchService) {
                $this->resultsSet[] = $searchService->getResultSet($query);
            }
        }
        return $this->getResultsSet();
    }

}
