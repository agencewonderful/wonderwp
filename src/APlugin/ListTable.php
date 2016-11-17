<?php

namespace WonderWp\APlugin;

use Doctrine\ORM\Mapping\ClassMetadata;
use WonderWp\DI\Container;
use WonderWp\HttpFoundation\Request;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class ListTable extends \WP_List_Table
{

    /* @var \Doctrine\ORM\EntityManager */
    protected $_em;

    /* @var string */
    protected $_entityName;

    /* @var  ClassMetadata */
    protected $_entityMetas;

    /* @var array */
    protected $_columns;

    protected $_textDomain;

    public function __construct($args = array())
    {
        //Extend args
        if (!empty($args['entityName'])) {
            $this->_entityName = $args['entityName'];
        }
        if (!empty($args['textDomain'])) {
            $this->_textDomain = $args['textDomain'];
        }

        $this->_em = Container::getInstance()->offsetGet('entityManager');

        parent::__construct($args);

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->_entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->_entityName = $entityName;
    }

    /**
     * @return mixed|string
     */
    public function getTextDomain()
    {
        return $this->_textDomain;
    }

    /**
     * @param mixed|string $textDomain
     */
    public function setTextDomain($textDomain)
    {
        $this->_textDomain = $textDomain;
    }

    public function prepare_items($filters=array(),$orderBy=array('id'=>'DESC'))
    {
        $items = array();

        if (!empty($this->_entityName)) {
            $repository = $this->_em->getRepository($this->_entityName);

            $qb = $this->_em->createQueryBuilder();

            //total count
            $qb->select($qb->expr()->count('e'))
                ->from($this->_entityName, 'e');

            $totalItems = $qb->getQuery()->getSingleScalarResult();	//Number of elements
            $perPage = 20;		//How many to display per page
            $paged = Request::getInstance()->get('paged',1); if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }	//Page Number
            $totalPages = ceil($totalItems/$perPage); //Total number of pages
            $offset=($paged-1)*$perPage;

            $this->items = $repository->findBy($filters,$orderBy,$perPage,$offset);
        }

        //Register the pagination
        $this->set_pagination_args( array(
            "total_items" => $totalItems,
            "total_pages" => $totalPages,
            "per_page" => $perPage,
        ) );

        $this->_defineColumnHeaders();

        return $this;
    }

    protected function _defineColumnHeaders()
    {
        //Register the Columns
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    public function get_columns()
    {
        if (empty($this->_columns)) {
            $this->_columns = array();
            $bulkActions = $this->get_bulk_actions();
            if (!empty($bulkActions)) {
                $this->_columns['cb'] = '<input type="checkbox" />';
            }

            $this->_entityMetas = $this->_em->getClassMetaData($this->_entityName);
            if (!empty($this->_entityMetas->fieldNames)) {
                foreach ($this->_entityMetas->fieldNames as $fieldName) {
                    $this->_columns[$fieldName] = __($fieldName . '.trad', $this->_textDomain);
                }
            }
            $this->_columns["action"] = __("Actions", $this->_textDomain);
        }
        return $this->_columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => __('Delete')
        );
        return $actions;
    }

    function extra_tablenav($which, $showAdd = true, $givenEditParams = array())
    {
        if($showAdd) {
            $request = Request::getInstance();
            $defaultEditParams = array(
                'page' => $request->get('page'),
                'action' => 'edit'
            );
            $editParams = \WonderWp\array_merge_recursive_distinct($defaultEditParams,$givenEditParams);
            $editPage = admin_url('/admin.php?' . http_build_query($editParams));
            $addBtn = '<a href="' . $editPage . '" class="button action noewpaddrecordbtn">' . esc_html_x('Add New', 'link') . '</a>';
            echo $addBtn;
        }
    }

    function column_cb($item)
    {
        $identifier = $this->_entityMetas->getIdentifier();
        return sprintf(
            '<input type="checkbox" name="item[]" value="%s" />', $this->_getItemVal($item, reset($identifier))
        );
    }

    /**
     * The default action to perform if nothing is prepared
     * @param object $item
     * @param string $column_name
     */
    public function column_default($item, $column_name)
    {
        $val = $this->_getItemVal($item, $column_name);
        echo $this->_formatVal($val);
    }

    protected function _getItemVal($item, $column_name)
    {
        $val = '';
        if (is_object($item)) {
            if (method_exists($item, 'get' . ucfirst($column_name))) {
                $val = call_user_func(array($item, 'get' . ucfirst($column_name)));
            } else {
                $val = $item->$column_name;
            }
        } elseif (is_array($item)) {
            $val = $item[$column_name];
        }
        return $val;
    }

    protected function _formatVal($val)
    {
        $valType = gettype($val);
        if ($valType === 'object') {
            $calledClass = get_class($val);
            if ($calledClass == 'DateTime') {
                $val = $val->format('d/m/Y H:i');
            }
        }
        return $val;
    }

    /**
     * Get the default noewp row actions (edit, delete, duplicate...)
     * @param object $item
     * @param array $allowedActions , you can narrow down the things to return
     */
    function column_action($item, $allowedActions = array('edit', 'delete'), $givenEditParams = array(), $givenDeleteParams = array())
    {
        $request = Request::getInstance();
        $identifier = $this->_entityMetas->getIdentifier();
        $defaultEditParams = array(
            'page' => $request->get('page'),
            'action' => 'edit',
            'id' => $this->_getItemVal($item, reset($identifier))
        );
        $editParams = \WonderWp\array_merge_recursive_distinct($defaultEditParams, $givenEditParams);
        $defaultDeleteParams = $editParams;
        $defaultDeleteParams['action'] = 'delete';
        $deleteParams = \WonderWp\array_merge_recursive_distinct($defaultDeleteParams,$givenDeleteParams);
        if (in_array('edit', $allowedActions)) {
            echo ' <a class="edit-link" href="' . admin_url('/admin.php?' . http_build_query($editParams)) . '">' . __('Edit') . '</a>';
        }
        if (in_array('delete', $allowedActions)) {
            echo ' <a class="delete-link" href="' . admin_url('/admin.php?' . http_build_query($deleteParams)) . '">' . __('Delete') . '</a>';
        }
    }

}