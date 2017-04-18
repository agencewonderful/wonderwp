<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 18/04/2017
 * Time: 17:58
 */

namespace WonderWp\Framework\Search;

interface SearchResultsRendererInterface
{
    /**
     * @param array $opts
     *
     * @return mixed
     */
    public function getMarkup(array $results, array $opts = []);
}
