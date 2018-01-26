<?php

namespace WonderWp\Framework\Search\Renderer;

use WonderWp\Framework\DependencyInjection\Container;
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
     * @param string                   $query
     * @param array                    $opts
     *
     * @return string
     */
    public function getSetMarkup(SearchResultSetInterface $set, $query, array $opts = [])
    {
        $totalCount = $set->getTotalCount();

        if ($totalCount <= 0) {
            return '';
        }

        $markup  = '';
        $results = $set->getCollection();
        if (!empty($results)) {

            $markup .=
                '<div class="search-result-set search-result-set-'.(!empty($opts['view']) ? $opts['view'] : 'extrait').' search-result-set-'.sanitize_title($set->getName()).'">
                <div class="seat-head"> ' .
                '<span class="set-total">' . (int)$totalCount . '</span> ' .
                '<span class="set-title">' . $set->getLabel() . '</span>
                </div>
                <ul class="set-results">';

            foreach ($results as $res) {
                $markup .= '<li>';
                if (!empty($res->getLink())) {
                    $markup .= '<a href="' . $res->getLink() . '">';
                }
                $markup .= '<span class="res-title">' . $res->getTitle() . '</span>';

                if (!empty($res->getContent())) {
                    $markup .= '<div class="res-content">' . $this->getMeaningFulContent($res->getContent(), $query) . '</div>';
                }

                if (!empty($res->getLink())) {
                    $markup .= '</a>';
                }
                $markup .= '</li>';
            }

            $markup .= '
                </ul>';

            $isListView = isset($opts['view']) && $opts['view'] === 'list';

            $baseQueryComponents = [
                's' => urlencode($query),
                't' => $opts['search_service'],
                'v' => 'list',
            ];

            if ($isListView) {
                $container           = Container::getInstance();
                $paginationComponent = $container['wwp.theme.component.pagination'];
                $markup              .= $paginationComponent->getMarkup([
                    'nbObjects'     => $totalCount,
                    'perPage'       => $opts['limit'],
                    'paginationUrl' => '/?' . http_build_query($baseQueryComponents + ['pageno' => '{pageno}']),
                    'currentPage'   => $opts['page'],
                ]);
            } else {
                if (!isset($opts['limit']) || (isset($opts['limit']) && $totalCount > $opts['limit'])) {
                    $markup .= '<a href="/?' . http_build_query($baseQueryComponents) . '">' . __('see.all.results', WWP_THEME_TEXTDOMAIN) . '</a>';
                }
            }

            $markup .=
                '</div>' .
                '</div>';

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

    protected function getMeaningFulContent($content, $query)
    {
        return substr(strip_tags(trim($content)), 0, 140);
    }
}
