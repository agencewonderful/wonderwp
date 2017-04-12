<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:54
 */

namespace WonderWp\Framework\Search;

class SearchResultSetsRenderer extends AbstractSearchResultSetRenderer
{
    public function getMarkup(array $opts = [])
    {
        $markup = '<div class="search-results">';
        if (!empty($this->sets)) {
            foreach ($this->sets as $set) {
                $markup .= $this->getSetMarkup($set);
            }
        } else {
            $markup = $this->getNoResultMarkup($opts);
        }
        $markup.='</div>';

        return $markup;
    }

    public function getSetMarkup(SearchResultSetInterface $set)
    {
        $markup  = '<div class="search-result-set">'
            . '<span class="set-title">' . $set->getLabel() . '</span>'
            . '<span class="set-total">' . (int)$set->getTotalCount() . '</span>';
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
        $markup .= '</div>';

        return $markup;
    }

    public function getNoResultMarkup(array $opts = [])
    {
        return 'Pas de résultat';
    }
}
