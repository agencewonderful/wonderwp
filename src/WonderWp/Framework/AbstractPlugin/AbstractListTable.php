<?php

namespace WonderWp\Framework\AbstractPlugin;

use function WonderWp\Framework\array_merge_recursive_distinct;
use WonderWp\Framework\HttpFoundation\Request;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

abstract class AbstractListTable extends \WP_List_Table
{
    /* @var array */
    protected $columns;
    /** @var string */
    protected $textDomain;

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
        if ($showAdd) {
            $request           = Request::getInstance();
            $defaultEditParams = [
                'page'   => $request->get('page'),
                'action' => 'edit',
            ];
            $editParams        = array_merge_recursive_distinct($defaultEditParams, $givenEditParams);
            $editPage          = admin_url('/admin.php?' . http_build_query($editParams));
            $addBtn            = '<a href="' . $editPage . '" class="button action noewpaddrecordbtn">' . esc_html_x('Add New', 'link') . '</a>';
            echo $addBtn;
        }
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
