<?php

namespace WonderWp\Entity;

use WonderWp\DI\Container;

abstract class AbstractEntity {

    public function __get($propertyName) {
        if(method_exists($this,'get'.ucfirst($propertyName))){
            return call_user_func(array($this,'get'.ucfirst($propertyName)));
        }
        else if(property_exists($this,$propertyName)) {
            return $this->{$propertyName};
        }

        return null;
    }

    public function populate($data){
        if(!empty($data)){ foreach($data as $propertyName=>$val){
            if(method_exists($this,'set'.ucfirst($propertyName))){
                call_user_func(array($this,'set'.ucfirst($propertyName)),$val);
            } else {
                $this->$propertyName = $val;
            }
        }}
        return $this;
    }

    /**
     * Get object attributes
     * @todo attention a la perf, verifier que getClassMetaData est appele qu'une seule fois par $entityName et pas une fois par call
     * @return array
     */
    public function getAttributes(){
        $entityName = get_class($this);

        $container = Container::getInstance();
        $entityManager = $container->offsetGet('entityManager');
        $metas = $entityManager->getClassMetaData($entityName);
        $mapping = !empty($metas->fieldMappings) ? $metas->fieldMappings : array();

        $attributes = array();

        if(!empty($mapping)){ foreach($mapping as $attrInfos){
            $attributes[$attrInfos['fieldName']] = new EntityAttribute($attrInfos);
        }}

        return $attributes;
    }

}