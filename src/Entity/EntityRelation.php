<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 06/09/2016
 * Time: 10:39
 */

namespace WonderWp\Entity;

class EntityRelation{

    private $_fieldName;
    private $_type;
    private $_mappedBy;
    private $_targetEntity;
    private $_sourceEntity;

    /**
     * EntityRelation constructor.
     * @param array $attributes
     * @return EntityRelation
     */
    public function __construct($attributes = array())
    {
        $this->_fieldName = !empty($attributes['fieldName']) ? $attributes['fieldName'] : '';
        $this->_type = !empty($attributes['type']) ? $attributes['type'] : '';
        $this->_mappedBy = !empty($attributes['mappedBy']) ? $attributes['mappedBy'] : '';
        $this->_targetEntity = !empty($attributes['targetEntity']) ? $attributes['targetEntity'] : '';
        $this->_sourceEntity = !empty($attributes['sourceEntity']) ? $attributes['sourceEntity'] : false;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getFieldName()
    {
        return $this->_fieldName;
    }

    /**
     * @param mixed|string $fieldName
     * @return $this
     */
    public function setFieldName($fieldName)
    {
        $this->_fieldName = $fieldName;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMappedBy()
    {
        return $this->_mappedBy;
    }

    /**
     * @param mixed|string $mappedBy
     * @return $this
     */
    public function setMappedBy($mappedBy)
    {
        $this->_mappedBy = $mappedBy;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTargetEntity()
    {
        return $this->_targetEntity;
    }

    /**
     * @param mixed|string $targetEntity
     * @return $this
     */
    public function setTargetEntity($targetEntity)
    {
        $this->_targetEntity = $targetEntity;
        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getSourceEntity()
    {
        return $this->_sourceEntity;
    }

    /**
     * @param bool|mixed $sourceEntity
     * @return $this
     */
    public function setSourceEntity($sourceEntity)
    {
        $this->_sourceEntity = $sourceEntity;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param mixed|string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

}