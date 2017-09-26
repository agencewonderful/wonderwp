<?php

namespace WonderWp\Framework\AbstractPlugin;

use function WonderWp\Framework\array_merge_recursive_distinct;
use WonderWp\Framework\Filter\FilterFormService;
use WonderWp\Framework\Filter\FilterFormServiceInterface;
use WonderWp\Framework\HttpFoundation\Request;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

abstract class AbstractListTable extends \WP_List_Table
{
    /* @var array */
    protected $columns = [];
    /** @var string */
    protected $textDomain;

    /** @var  FilterFormServiceInterface */
    protected $filterFormService;

    /**
     * @param array $args
     */
    public function __construct($args = [])
    {
        if (array_key_exists('textDomain', $args)) {
            $this->textDomain = $args['textDomain'];
        }

        parent::__construct($args);
    }

    /**
     * @return FilterFormServiceInterface
     */
    public function getFilterFormService()
    {
        return $this->filterFormService;
    }

    /**
     * @param FilterFormServiceInterface $filterFormService
     *
     * @return static
     */
    public function setFilterFormService($filterFormService)
    {
        $this->filterFormService = $filterFormService;

        return $this;
    }

    /**
     * @param array $filters
     * @param array $orderBy
     */
    abstract protected function doPrepareItems(array $filters = [], array $orderBy = ['id' => 'DESC']);

    /**
     * @param array $filters
     * @param array $orderBy
     *
     * @return static
     */
    public function prepare_items($filters = [], $orderBy = ['id' => 'DESC'])
    {
        $this->items = [];

        $this->doPrepareItems($filters, $orderBy);

        $this->defineColumnHeaders();

        return $this;
    }

    protected function defineColumnHeaders()
    {
        //Register the Columns
        $columns               = $this->get_columns();
        $hidden                = $this->get_hidden_columns();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = [$columns, $hidden, $sortable];
    }

    /**
     * @return array
     */
    public function get_columns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function get_hidden_columns()
    {
        return [];
    }

    /**
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'delete' => __('Delete'),
        ];

        return $actions;
    }

    /**
     * @param string $which
     * @param bool   $showAdd
     * @param array  $givenEditParams
     */
    public function extra_tablenav($which, $showAdd = true, $givenEditParams = [])
    {
        $request           = Request::getInstance();
        $defaultPageParams = [
            'page'   => $request->get('page'),
        ];

        if ($which === 'top') {
            $filtersView = $this->getFiltersView($request->query->all());
            if (!empty($filtersView)) {
                echo '<div class="wp-filter">
                    <div class="filter-items">
                        ' . $filtersView . '                        
                    </div>
                    <a href="' . admin_url('/admin.php?' . http_build_query($defaultPageParams)) . '" title="' . __('Clear Filters') . '" class="clear-filters">&times;</a>
                </div>';
            }
        }

        if ($showAdd) {
            $defaultEditParams           = $defaultPageParams;
            $defaultEditParams['action'] = 'edit';
            $editParams                  = array_merge_recursive_distinct($defaultEditParams, $givenEditParams);
            $editPage                    = admin_url('/admin.php?' . http_build_query($editParams));
            $addBtn                      = '<a href="' . $editPage . '" class="button action noewpaddrecordbtn">' . esc_html_x('Add New', 'link') . '</a>';
            echo $addBtn;
        }

    }

    public function bulk_actions($which = '')
    {
        if (empty($this->get_bulk_actions())) {
            return;
        } else {
            echo '<form id="list_class_bulk_actions" method="post">';
            parent::bulk_actions($which);
            echo '</form>';
        }
    }

    public function getFiltersView($formData = [])
    {
        $filtersView = null;
        if ($this->filterFormService instanceof FilterFormServiceInterface) {
            /** @var FilterFormService $filterFormService */
            $filterForm  = $this->filterFormService->buildFiltersForm($formData);
            $viewParams  = $this->filterFormService->getFormViewParams();
            $filtersView = $filterForm->renderView($viewParams);
        }

        return $filtersView;
    }

    /**
     * The default action to perform if nothing is prepared
     *
     * @param object $item
     * @param string $column_name
     */
    public function column_default($item, $column_name)
    {
        $val = $this->getItemVal($item, $column_name);
        echo $this->formatVal($val);
    }

    /**
     * @param mixed  $item
     * @param string $columnName
     *
     * @return mixed|string
     */
    protected function getItemVal($item, $columnName)
    {
        $val = '';
        if (is_object($item)) {
            if (method_exists($item, 'get' . ucfirst($columnName))) {
                $val = call_user_func([$item, 'get' . ucfirst($columnName)]);
            } else {
                $val = $item->$columnName;
            }
        } elseif (is_array($item)) {
            $val = $item[$columnName];
        }

        if (is_string($val)) {
            $val = stripslashes($val);
            if (!preg_match('!!u', $val)) {
                $val = utf8_encode($val);
            }
        }

        return $val;
    }

    /**
     * @param mixed $val
     *
     * @return string
     */
    protected function formatVal($val)
    {
        if ($val instanceof \DateTime) {
            $val = $val->format('d/m/Y H:i');
        }

        if (is_array($val) || is_object($val)) {
            return json_encode($val);
        }

        return $val;
    }

    /**
     * @return mixed|string
     */
    public function getTextDomain()
    {
        return $this->textDomain;
    }

    /**
     * @param mixed|string $textDomain
     */
    public function setTextDomain($textDomain)
    {
        $this->textDomain = $textDomain;
    }
}
