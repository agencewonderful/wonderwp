<?php

namespace WonderWp\APlugin;

use Doctrine\ORM\Mapping\ClassMetadata;
use WonderWp\DI\Container;
use WonderWp\HttpFoundation\Request;

class ListTable extends \WP_List_Table{

    /* @var \WonderWp\DI\Container */
    protected $_container;

    /* @var \Doctrine\ORM\EntityManager */
    protected $_em;

    /* @var string */
    protected $_entityName;

    /* @var  ClassMetadata*/
    protected $_entityMetas;

    /* @var array */
    protected $_columns;

    protected $_textDomain;

    public function __construct($args = array())
    {
        //Extend args
        if(!empty($args['entityName'])){ $this->_entityName=$args['entityName']; }
        $this->_textDomain = !empty($args['textdomain']) ? $args['textdomain'] : 'default';

        $this->_container = Container::getInstance();
        $this->_em = $this->_container->offsetGet('entityManager');

        parent::__construct($args);

        return $this;
    }

    public function prepare_items()
    {
        $items = array();
        if(!empty($this->_entityName)) {
            $repository = $this->_em->getRepository($this->_entityName);
            $this->items = $repository->findAll();
        }

        $this->_defineColumnHeaders();

        return $this;
    }

    protected function _defineColumnHeaders(){
        //Register the Columns
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    public function get_columns()
    {
        if(empty($this->_columns)) {
            $this->_columns=array();
            $bulkActions = $this->get_bulk_actions();
            if(!empty($bulkActions)){
                $this->_columns['cb'] = '<input type="checkbox" />';
            }

            $this->_entityMetas = $this->_em->getClassMetaData($this->_entityName);
            if(!empty($this->_entityMetas->fieldNames)){ foreach($this->_entityMetas->fieldNames as $fieldName){
                $this->_columns[$fieldName] = __($fieldName.'.trad',$this->_textDomain);
            }}
            $this->_columns["action"] = __("Actions",$this->_textDomain);
        }
        return $this->_columns;
    }

    public function get_hidden_columns(){
        return array();
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => __('Delete')
        );
        return $actions;
    }

    function extra_tablenav( $which, $showAdd = true ) {
        $request = Request::getInstance();
        $editParams = array(
            'page'=>$request->get('page'),
            'action'=>'edit'
        );
        $editPage = admin_url('/admin.php?'.http_build_query($editParams));
        $addBtn = '<a href="'.$editPage.'" class="button action noewpaddrecordbtn">'.__('addRecord.trad').'</a>';
        echo $addBtn;
    }

    function column_cb($item) {
        $identifier = $this->_entityMetas->getIdentifier();
        return sprintf(
            '<input type="checkbox" name="item[]" value="%s" />', $this->_getItemVal($item,reset($identifier))
        );
    }

    /**
     * The default action to perform if nothing is prepared
     * @param object $item
     * @param string $column_name
     */
    public function column_default($item, $column_name){
        $val = $this->_getItemVal($item,$column_name);
        echo $this->_formatVal($val);
    }

    private function _getItemVal($item,$column_name){
        $val = '';
        if(is_object($item)){
            if ( method_exists( $item, 'get' .ucfirst($column_name)  ) ) {
                $val = call_user_func(array($item,'get' .ucfirst($column_name)));
            } else {
                $val = $item->$column_name;
            }
        } elseif(is_array($item)) {
            $val = $item[$column_name];
        }
        return $val;
    }

    private function _formatVal($val){
        $valType = gettype($val);
        if($valType==='object'){
            $calledClass = get_class($val);
            if($calledClass=='DateTime'){
                $val = $val->format('d/m/Y H:i');
            }
        }
        return $val;
    }

    /**
     * Get the default noewp row actions (edit, delete, duplicate...)
     * @param object $item
     * @param array $allowedActions, you can narrow down the things to return
     */
    function column_action($item,$allowedActions=array('edit','delete')){
        $request = Request::getInstance();
        $identifier = $this->_entityMetas->getIdentifier();
        $editParams = array(
            'page'=>$request->get('page'),
            'action'=>'edit',
            'id'=>$this->_getItemVal($item,reset($identifier))
        );
        $deleteParams=$editParams;
        $deleteParams['action']='delete';
        if(in_array('edit',$allowedActions)){ echo '<a href="'.admin_url('/admin.php?'.http_build_query($editParams)).'">Editer</a>'; }
        if(in_array('delete',$allowedActions)){ echo '<a href="'.admin_url('/admin.php?'.http_build_query($deleteParams)).'">Supprimer</a>'; }
    }

}