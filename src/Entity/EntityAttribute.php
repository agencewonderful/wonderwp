<?php

namespace WonderWp\Entity;

class EntityAttribute
{

    private $_fieldName;
    private $_type;
    private $_length;
    private $_unique;
    private $_nullable;
    private $_columnName;
    private $_isId;

    /**
     * EntityAttribute constructor.
     * @param array $attributes
     * @return EntityAttribute
     */
    public function __construct($attributes = array())
    {
        $this->_fieldName = !empty($attributes['fieldName']) ? $attributes['fieldName'] : '';
        $this->_type = !empty($attributes['type']) ? $attributes['type'] : '';
        $this->_length = !empty($attributes['length']) ? $attributes['length'] : 0;
        $this->_unique = !empty($attributes['unique']) ? $attributes['unique'] : false;
        $this->_nullable = !empty($attributes['nullable']) ? $attributes['nullable'] : false;
        $this->_columnName = !empty($attributes['columnName']) ? $attributes['columnName'] : '';
        $this->_isId = !empty($attributes['id']) ? $attributes['id'] : 0;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->_fieldName;
    }

    /**
     * @param mixed $fieldName
     * @return $this
     */
    public function setFieldName($fieldName)
    {
        $this->_fieldName = $fieldName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->_length;
    }

    /**
     * @param mixed $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->_length = $length;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnique()
    {
        return $this->_unique;
    }

    /**
     * @param mixed $unique
     * @return $this
     */
    public function setUnique($unique)
    {
        $this->_unique = $unique;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNullable()
    {
        return $this->_nullable;
    }

    /**
     * @param mixed $nullable
     * @return $this
     */
    public function setNullable($nullable)
    {
        $this->_nullable = $nullable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumnName()
    {
        return $this->_columnName;
    }

    /**
     * @param mixed $columnName
     * @return $this
     */
    public function setColumnName($columnName)
    {
        $this->_columnName = $columnName;
        return $this;
    }

    /**
     * @return int|mixed
     */
    public function getIsId()
    {
        return $this->_isId;
    }

    /**
     * @param int|mixed $isId
     * @return $this
     */
    public function setIsId($isId)
    {
        $this->_isId = $isId;
        return $this;
    }

}