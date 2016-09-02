<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 31/08/2016
 * Time: 10:36
 */

namespace WonderWp\Panel;

class Panel implements PanelInterface{

    private $_id;
    private $_title;
    private $_fields = array();
    private $_postTypes = array();

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param mixed $params
     * @return $this
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    /**
     * @return array
     */
    public function getPostTypes()
    {
        return $this->_postTypes;
    }

    /**
     * @param mixed $postType
     * @return $this
     */
    public function setPostTypes(array $postTypes)
    {
        $this->_postTypes = $postTypes;
        return $this;
    }

}