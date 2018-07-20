<?php

namespace WonderWp\Framework\Repository;

class PostRepository implements RepositoryInterface
{
    const POST_TYPE = 'post';

    /**
     * @inheritDoc
     * @return \WP_Post
     */
    public function find($id)
    {
        return get_post($id);
    }

    /**
     * @inheritDoc
     * @return \WP_Post[]
     */
    public function findAll()
    {
        $criteria = [
            'numberpost' => -1,
            'post_type'  => static::POST_TYPE,
        ];

        return get_posts($criteria);
    }

    /**
     * @inheritDoc
     * @return \WP_Post[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {

        if (empty($criteria['post_type'])) {
            $criteria['post_type'] = static::POST_TYPE;
        }

        if (!empty($limit)) {
            $criteria['numberposts'] = $limit;
        } else {
            $criteria['numberposts'] = -1;
        }
        if (!empty($offset)) {
            $criteria['offset'] = $offset;
        }

        if (!empty($orderBy)) {
            $criteria['orderby'] = array_keys($orderBy);
            $criteria['order']   = array_values($orderBy);

            if (count($orderBy) == 1) {
                $criteria['orderby'] = reset($criteria['orderby']);
                $criteria['order']   = reset($criteria['order']);
            }
        }

        return get_posts($criteria);
    }

    /**
     * @inheritDoc
     * @return \WP_Post
     */
    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * @inheritDoc
     */
    public function getClassName()
    {
        return \WP_Post::class;
    }

    /**
     * Count items for given criteria.
     *
     * @param  array $criteria
     *
     * @return integer
     */
    public function countBy(array $criteria)
    {
        // TODO - Optimize
        return count($this->findBy($criteria));
    }

    public function getTermsForTaxonomy(array $args = [])
    {
        return get_terms($args);
    }
}
