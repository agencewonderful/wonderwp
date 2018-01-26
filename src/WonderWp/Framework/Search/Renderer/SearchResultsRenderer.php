<?php

namespace WonderWp\Framework\Search\Renderer;

abstract class SearchResultsRenderer implements SearchResultsRendererInterface
{
    /**
     * @inheritDoc
     */
    abstract public function getMarkup(array $results, array $opts = []);

}
