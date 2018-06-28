<?php

namespace WonderWp\Framework\Panel;

use WonderWp\Framework\Form\Field\FieldInterface;

class Panel implements PanelInterface
{
    /** @var string */
    protected $id;
    /** @var string */
    protected $title;
    /** @var FieldInterface[] */
    protected $fields = [];
    /** @var array */
    protected $postTypes = [];

    /** @inheritdoc */
    public function getId()
    {
        return $this->id;
    }

    /** @inheritdoc */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /** @inheritdoc */
    public function getTitle()
    {
        return $this->title;
    }

    /** @inheritdoc */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /** @inheritdoc */
    public function getFields()
    {
        return $this->fields;
    }

    /** @inheritdoc */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /** @inheritdoc */
    public function getPostTypes()
    {
        return $this->postTypes;
    }

    /** @inheritdoc */
    public function setPostTypes(array $postTypes)
    {
        $this->postTypes = $postTypes;

        return $this;
    }
}
