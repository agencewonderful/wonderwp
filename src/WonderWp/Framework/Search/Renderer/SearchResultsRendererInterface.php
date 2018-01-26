<?php

namespace WonderWp\Framework\Search\Renderer;

interface SearchResultsRendererInterface
{
    /**
     * @param array $results
     * @param string $query
     * @param array $opts
     *
     * @return mixed
     */
    public function getMarkup(array $results, $query, array $opts = []);
}
