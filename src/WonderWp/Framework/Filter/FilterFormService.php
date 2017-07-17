<?php

namespace WonderWp\Framework\Filter;

use function WonderWp\Framework\array_diff_recursive;
use function WonderWp\Framework\array_filter_recursive;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Form\Field\FieldInterface;
use WonderWp\Framework\Form\Field\HiddenField;
use WonderWp\Framework\Form\FormInterface;
use WonderWp\Framework\HttpFoundation\Request;
use function WonderWp\Framework\trace;

class FilterFormService implements FilterFormServiceInterface
{

    /**
     * @var FilterServiceInterface
     */
    protected $filterService;

    /**
     * FilterFormService constructor.
     *
     * @param FilterServiceInterface $filterService
     */
    public function __construct(FilterServiceInterface $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * @return FilterServiceInterface
     */
    public function getFilterService()
    {
        return $this->filterService;
    }

    /**
     * @param FilterServiceInterface $filterService
     *
     * @return static
     */
    public function setFilterService($filterService)
    {
        $this->filterService = $filterService;

        return $this;
    }

    /** @inheritdoc */
    public function buildFiltersForm(array $data = [])
    {
        /** @var FormInterface $form */
        $form = Container::getInstance()->offsetGet('wwp.forms.form');
        $form->setName('wwp-bo-filters-form');

        $filters = $this->filterService->getFilters();

        if (!empty($filters)) {
            foreach ($filters as $f) {
                /** @var Filter $f */
                $field         = $f->getField();
                $attributePath = $f->getAttributePath();
                $frags         = explode('.', $attributePath);
                //Transform directly html field names to arrays to follow attribute path
                if (count($frags) > 1) {
                    $displayName = '';
                    foreach ($frags as $i => $part) {
                        $displayName .= $i == 0 ? $part : '[' . $part . ']';
                    }

                    $displayRules = $field->getDisplayRules();
                    if (!isset($displayRules['inputAttributes'])) {
                        $displayRules['inputAttributes'] = [];
                    }
                    $displayRules['inputAttributes']['name'] = $displayName;
                    $field->setDisplayRules($displayRules);
                }
                $form->addField($f->getField());
            }
        }

        $originalFields = $this->getOriginalGetParametersFields();
        if (!empty($originalFields)) {
            foreach ($originalFields as $originalField) {
                $form->addField($originalField);
            }
        }

        return $form;
    }

    /**
     * @return FieldInterface[]
     */
    protected function getOriginalGetParametersFields()
    {
        $originalFields = [];

        //Aditional fields not to loose existing get parameters
        $queryParams     = Request::getInstance()->query->all();
        $extracted       = $this->extractFilters($queryParams, true);

        $nativeGetParams = array_diff_recursive(
            $queryParams,
            $extracted
        );

        /*dump($queryParams);
        dump($extracted);
        dump($nativeGetParams);*/

        if (!empty($nativeGetParams)) {
            foreach ($nativeGetParams as $key => $val) {
                $originalFields[] = new HiddenField($key, $val);
            }
        }

        return $originalFields;
    }

    public function extractFilters(array $data, $strict = false)
    {
        $extracted = [];
        $filters   = $this->filterService->getFilters();

        if (!empty($filters)) {
            foreach ($filters as $f) {
                /** @var Filter $f */
                $path = $f->getAttributePath();
                $pathFrags = explode('.',$path);
                $name = reset($pathFrags);

                if($strict){
                    $extracted[$name] = !empty($data[$name]) ? $data[$name] : null;
                } else {
                    if (isset($data[$name]) && is_array($data[$name])) {
                        $data[$name] = array_filter_recursive($data[$name]);
                    }
                    if (!empty($data[$name])) {
                        $extracted[$name] = $data[$name];
                    }
                }
            }
        }

        return $extracted;
    }

    public function getFormViewParams()
    {
        $viewParams = [
            'formStart' => [
                'method' => 'get',
            ],
            'formEnd'   => [
                'submitLabel' => 'Filtrer',
            ],
        ];

        return $viewParams;
    }

    public function getFormAction(array $queryParams)
    {

        $filters = $this->filterService->getFilters();

        if (!empty($filters)) {
            foreach ($filters as $f) {
                /** @var Filter $f */
                $name = $f->getField()->getName();
                if (isset($queryParams[$name])) {
                    unset($queryParams[$name]);
                }
            }
        }

        return;
    }

}
