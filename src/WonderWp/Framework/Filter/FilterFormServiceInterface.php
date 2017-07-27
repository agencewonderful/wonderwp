<?php

namespace WonderWp\Framework\Filter;

use WonderWp\Framework\Form\FormInterface;

interface FilterFormServiceInterface
{

    /**
     * @return FilterServiceInterface
     */
    public function getFilterService();

    /**
     * @param FilterServiceInterface $filterService
     *
     * @return static
     */
    public function setFilterService($filterService);

    /**
     * @param array    $data
     *
     * @return mixed
     */
    public function buildFiltersForm(array $data = []);

    /**
     * @param array $data
     * @param bool  $strict
     *
     * @return array
     */
    public function extractFilters(array $data, $strict=false);

    /**
     * If you want to pass specific form view params
     * @return array
     */
    public function getFormViewParams();

}
