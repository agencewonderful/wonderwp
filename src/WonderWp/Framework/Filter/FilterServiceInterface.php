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
}
