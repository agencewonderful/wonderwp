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
                '<div class="search-result-set search-result-set-' . (!empty($opts['view']) ? $opts['view'] : 'extrait') . ' search-result-set-' . sanitize_title($set->getName()) . '">
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
                $markup .= '<span class="res-title">' . $this->highlightSearchTerm($res->getTitle(), $query) . '</span>';

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
                    $markup .= '<a href="/?' . http_build_query($baseQueryComponents) . '" class="search-all-res-in-cat">' . __('see.all.results', WWP_THEME_TEXTDOMAIN) . '</a>';
                }
            }

            $markup .=
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

        $text    = str_replace(["\r\n", "\r"], " ", strip_tags($content));
        $testpos = !empty($query) ? strpos(strtolower($text), strtolower($query)) : 0;
        $size    = 140;
        $half    = ceil($size / 2);
        $mindif  = $testpos - $half;
        $maxdif  = $testpos + $half;

        if ($mindif < 0) {
            $minbound = 0;
            $pre_     = '';
        } else {
            $minbound = $mindif;
            $pre_     = '...';
        }
        if ($maxdif > $size) {
            $maxbound = $size;
            $sr_      = '...';
        } else {
            $maxbound = $maxdif;
            $sr_      = '...';
        }

        $text = $pre_ . substr($text, $minbound, $maxbound) . $sr_;
        if ($text == '...') {
            $text = '';
        }

        //$text = str_ireplace($query, '<span class="match">' . $query . '</span>', $text, $c2);
        $text = $this->highlightSearchTerm($text, $query);

        return $text;

    }

    /**
     * Hihglight search term in search results markup
     *
     * @param string $text   , search result text
     * @param string $search , the search term
     *
     * @return string
     */
    protected function highlightSearchTerm($text, $search)
    {

        $delim  = '#';
        $search = preg_quote($search, $delim);

        $search = preg_replace('/[aàáâãåäæ]/iu', '[aàáâãåäæ]', $search);
        $search = preg_replace('/[eèéêë]/iu', '[eèéêë]', $search);
        $search = preg_replace('/[iìíîï]/iu', '[iìíîï]', $search);
        $search = preg_replace('/[oòóôõöø]/iu', '[oòóôõöø]', $search);
        $search = preg_replace('/[uùúûü]/iu', '[uùúûü]', $search);

        return preg_replace('#' . $search . '#iu', '<span class="match">$0</span>', $text);
        //return $text = str_ireplace($search, '<span class="match">' . $search . '</span>', $text, $c2);
    }
}
