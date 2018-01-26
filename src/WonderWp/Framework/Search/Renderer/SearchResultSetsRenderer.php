<?php

namespace WonderWp\Framework\Search\Renderer;

use WonderWp\Framework\Search\ResultSet\SearchResultSetInterface;

class SearchResultSetsRenderer implements SearchResultsRendererInterface
{
    /**
     * @var SearchResultSetInterface[]
     */
    protected $sets = [];

    /**
     * @return SearchResultSetInterface[]
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @param SearchResultSetInterface[] $sets
     *
     * @return static
     */
    public function setSets($sets)
    {
        $this->sets = $sets;

        return $this;
    }

    /** @inheritdoc */
    public function getMarkup(array $results, $query, array $opts = [])
    {
        $this->setSets($results);

        $markup = '';
        if (!empty($this->sets)) {
            foreach ($this->sets as $set) {
                $markup .= $this->getSetMarkup($set, $query, $opts);
            }
        } else {
            $markup = $this->getNoResultMarkup($opts);
        }

        return $markup;
    }

    /**
     * @param SearchResultSetInterface $set
     * @param string $query
     * @param array $opts
     *
     * @return string
     */
    public function getSetMarkup(SearchResultSetInterface $set, $query, array $opts = [])
    {
        if ($set->getTotalCount() <= 0) {
            return '';
        }

        $markup = '';
        $results = $set->getCollection();
        if (!empty($results)) {
            $markup .= '<ul>';
            foreach ($results as $res) {
                $markup .= '<li>';
                if (!empty($res->getLink())) {
                    $markup .= '<a href="' . $res->getLink() . '">';
                }
                $markup .= '<span class="res-title">' . $res->getTitle() . '</span>';

                if (!empty($res->getContent())) {
                    $markup .= '<div class="res-content">' . $res->getContent() . '</div>';
                }

                if (!empty($res->getLink())) {
                    $markup .= '</a>';
                }
                $markup .= '</li>';
            }
            $markup .= '</ul>';
        }

        return $markup;
    }

    /**
     * @param array $opts
     *
     * @return string
     */
    public function getNoResultMarkup(array $opts = [])
    {
        return apply_filters('wwp.search.noresult', 'No result');
    }
}
