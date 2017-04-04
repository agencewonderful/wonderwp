<?php

namespace WonderWp\Framework\Panel;

use WonderWp\Framework\Form\Field\FieldInterface;

class Panel implements PanelInterface
{

    /**
     * @var string
     */
    private $_id;
    /**
     * @var string
     */
    private $_title;
    /**
     * @var FieldInterface[]
     */
    private $_fields    = [];
    /**
     * @var array
     */
    private $_postTypes = [];

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @inheritdoc
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPostTypes()
    {
        return $this->_postTypes;
    }

    /**
     * @inheritdoc
     */
    public function setPostTypes(array $postTypes)
    {
        $this->_postTypes = $postTypes;

        return $this;
    }

}
