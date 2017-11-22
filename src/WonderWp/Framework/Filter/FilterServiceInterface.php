<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/07/2017
 * Time: 18:03
 */

namespace WonderWp\Framework\Filter;

interface FilterServiceInterface
{

    const HANDLED_CLASS = null;
    const FILTER_SERVICE = 'filter';
    /**
     * @return Filter[]
     */
    public function getFilters();

    /**
     * @param Filter[] $filters
     *
     * @return static
     */
    public function setFilters($filters);

    /**
     * @param Filter $filter
     *
     * @return static
     */
    public function addFilter(Filter $filter);
}
