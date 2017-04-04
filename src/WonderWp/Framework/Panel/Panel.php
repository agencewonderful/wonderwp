<?php

namespace WonderWp\Framework\Panel;

class Panel implements PanelInterface
{

    private $_id;
    private $_title;
    private $_fields    = [];
    private $_postTypes = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     *
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
     *
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
     * @param mixed $fields
     *
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
     * @param array $postTypes
     *
     * @return $this
     */
    public function setPostTypes(array $postTypes)
    {
        $this->_postTypes = $postTypes;

        return $this;
    }

}
