<?php

namespace WonderWp\Entity;

use Doctrine\ORM\EntityManager;
use WonderWp\DI\Container;

abstract class AbstractEntity
{

    protected $_fields = array();
    protected $_relations = array();

    public function __get($propertyName)
    {
        if (method_exists($this, 'get' . ucfirst($propertyName))) {
            return call_user_func(array($this, 'get' . ucfirst($propertyName)));
        } else if (property_exists($this, $propertyName)) {
            return $this->{$propertyName};
        }

        return null;
    }

    public function __set($propertyName, $value)
    {
        if (method_exists($this, 'set' . ucfirst($propertyName))) {
            return call_user_func(array($this, 'set' . ucfirst($propertyName)), $value);
        } else if (property_exists($this, $propertyName)) {
            $this->{$propertyName} = $value;
        }
    }

    public function populate($data)
    {
        if (!empty($data)) {
            foreach ($data as $propertyName => $val) {
                if (method_exists($this, 'set' . ucfirst($propertyName))) {
                    call_user_func(array($this, 'set' . ucfirst($propertyName)), $val);
                } else {
                    $this->$propertyName = $val;
                }
            }
        }
        return $this;
    }

    /**
     * Get object attributes
     * @todo attention a la perf, verifier que getClassMetaData est appele qu'une seule fois par $entityName et pas une fois par call
     * @return array
     */
    public function getMapping()
    {
        $entityName = get_class($this);

        $container = Container::getInstance();
        $entityManager = $container->offsetGet('entityManager');
        /** @var EntityManager $entityManager*/
        $metas = $entityManager->getClassMetaData($entityName);

        //Fields
        $fields = !empty($metas->fieldMappings) ? $metas->fieldMappings : array();
        if (!empty($fields)) {
            foreach ($fields as $attrInfos) {
                $this->_fields[$attrInfos['fieldName']] = new EntityAttribute($attrInfos);
            }
        }

        //Relations
        $relations = !empty($metas->associationMappings) ? $metas->associationMappings : array();
        if (!empty($relations)) {
            foreach ($relations as $relInfos) {
                $this->_relations[$relInfos['fieldName']] = new EntityRelation($relInfos);
            }
        }

        $attributes = array('fields' => $this->_fields, 'relations' => $this->_relations);

        return $attributes;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        if(empty($this->_fields)){
            $this->getMapping();
        }
        return $this->_fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        if(empty($this->_relations)){
            $this->getMapping();
        }
        return $this->_relations;
    }

    /**
     * @param array $relations
     */
    public function setRelations($relations)
    {
        $this->_relations = $relations;
    }

    public function addRelatedEntity($fieldName,$entity,$callable){
        if (!$this->$fieldName->contains($entity)) {
            $this->$fieldName->add($entity);
            $entity->$callable($this);
        }
        return $this;
    }

    public function removeRelatedEntity($fieldName,$entity,$callable)
    {
        if ($this->$fieldName->contains($entity)) {
            $this->$fieldName->removeElement($entity);
            $entity->$callable(null);
        }
        return $this;
    }

}