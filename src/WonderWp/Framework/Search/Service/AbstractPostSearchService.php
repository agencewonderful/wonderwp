<?php

namespace WonderWp\Framework\Search\Service;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Media\Medias;
use WonderWp\Framework\Search\Result\SearchResultInterface;
use function WonderWp\Framework\trace;
use function WP_CLI\Utils\glob_brace;

abstract class AbstractPostSearchService extends AbstractSetSearchService
{
    const POST_TYPE = 'post';

    /**
     * @var \wpdb
     */
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    protected function giveSetName()
    {
        return static::POST_TYPE . '-set';
    }

    /** @inheritdoc */
    protected function giveSetTotalCount($query, array $opts = [])
    {

        $queryStr = $this->getQuerySql($query, [static::POST_TYPE], 'COUNT');
        $res      = $this->wpdb->get_results($queryStr);

        return !empty($res) && !empty($res[0]) && !empty($res[0]->cpt) ? $res[0]->cpt : 0;
    }

    /** @inheritdoc */
    protected function giveSetResults($query, array $opts = [])
    {

        $resCollection = [];
        $queryStr      = $this->getQuerySql($query, [static::POST_TYPE], 'SELECT');
        $queryStr      .= ' LIMIT ' . $opts['limit'] . ' OFFSET ' . $opts['offset'];
        $dbCollection  = $this->wpdb->get_results($queryStr);
        if (!empty($dbCollection)) {
            foreach ($dbCollection as $dbRow) {
                $resCollection[] = $this->mapToRes($dbRow);
            }
        }

        return $resCollection;
    }

    /**
     * Build sql query looking for a text in posts, for a given type and action.
     *
     * @param  string $searchText
     * @param  array  $postTypes
     * @param  string $action
     *
     * @return string
     */
    protected function getQuerySql($searchText, $postTypes = [], $action = "SELECT")
    {
        global $wpdb;

        if ($action == 'COUNT') {
            $queryStr = "SELECT COUNT(*) as cpt";
        } else {
            $queryStr = "SELECT $wpdb->posts.*, MATCH (" . implode(',', $this->getIndexedFields()) . ") AGAINST ('" . $searchText . "' IN NATURAL LANGUAGE MODE) as score";
        }
        $queryStr .= "
                    FROM $wpdb->posts";
        $queryStr .= "
                    WHERE 1 ";
        $queryStr .= " AND (";
        $queryStr .= "$wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private'";
        if (in_array('attachment', $postTypes)) {
            $queryStr .= " OR $wpdb->posts.post_status = 'inherit'";
        }
        $queryStr .= ")";
        if (!empty($postTypes)) {
            $queryStr .= "
                    AND $wpdb->posts.post_type IN ('" . implode(',', $postTypes) . "')
            ";
        }

        $queryStr .= "
        AND MATCH (" . implode(',', $this->getIndexedFields()) . ") AGAINST ('" . $searchText . "' IN NATURAL LANGUAGE MODE)";

        /*$queryStr .= "
                    GROUP BY ID
                    HAVING (searchtext LIKE '%$searchText%')
                    ORDER BY $wpdb->posts.post_date DESC
                ";*/

        /*if ($action == 'COUNT') {
            $queryStr .= ') as searchView';
        }*/

        if ($action == 'SELECT') {
            $queryStr .= "
            ORDER BY score DESC";
        }

        return $queryStr;
    }

    protected function getIndexedFields()
    {
        return [
            'post_title',
            'post_content',
            'post_excerpt',
            'post_name',
        ];
    }

    /**
     * Turn a post into a search result.
     *
     * @param  \WP_Post $post
     *
     * @return SearchResultInterface
     */
    protected function mapToRes($post)
    {
        /** @var SearchResultInterface $res */
        $res = Container::getInstance()->offsetGet('wwp.search.result');

        $res->setTitle($post->post_title);
        $res->setThumb(Medias::getFeaturedImage($post));
        $res->setContent($post->post_content);
        $post->filter = 'sample';
        $res->setLink(get_permalink($post));

        return $res;
    }
}
