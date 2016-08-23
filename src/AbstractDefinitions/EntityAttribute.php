<?php

namespace WonderWp\AbstractDefinitions;

class EntityAttribute{

    private $_fieldName;
    private $_type;
    private $_length;
    private $_unique;
    private $_nullable;
    private $_columnName;

    public function __construct($attributes=array())
    {
        $this->_fieldName = !empty($attributes['fieldName']) ? $attributes['fieldName'] : '';
        $this->_type = !empty($attributes['type']) ?$attributes['type'] : '';
        $this->_length = !empty($attributes['length']) ? $attributes['length'] : 0;
        $this->_unique = !empty($attributes['unique']) ? $attributes['unique'] : false;
        $this->_nullable = !empty($attributes['nullable']) ? $attributes['nullable'] : false;
        $this->_columnName = !empty($attributes['columnName']) ? $attributes['columnName'] : '';
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
     */
    public function setFieldName($fieldName)
    {
        $this->_fieldName = $fieldName;
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
     */
    public function setType($type)
    {
        $this->_type = $type;
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
     */
    public function setLength($length)
    {
        $this->_length = $length;
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
     */
    public function setUnique($unique)
    {
        $this->_unique = $unique;
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
     */
    public function setNullable($nullable)
    {
        $this->_nullable = $nullable;
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
     */
    public function setColumnName($columnName)
    {
        $this->_columnName = $columnName;
    }



}