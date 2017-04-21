<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 20:38
 */

namespace WonderWp\Framework\Search;

abstract class AbstractSearchService implements SearchServiceInterface
{
    /**
     * @var string
     */
    protected $name;

    /** @inheritdoc*/
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /** @inheritdoc*/
    public function getName()
    {
        return $this->name;
    }

    /** @inheritdoc*/
    public function getMarkup($query, array $opts = [])
    {
        return '';
    }
}
