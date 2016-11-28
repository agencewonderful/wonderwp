<?php

namespace WonderWp\Forms;

use WonderWp\DI\Container;
use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Fields\FieldGroup;
use WonderWp\Forms\Fields\FieldInterface;

class Form implements FormInterface
{

    protected $_name;

    protected $_fields;

    protected $_errors;

    protected $_groups;

    /**
     * Form constructor.
     * @param $_name
     * @param $_fields
     */
    public function __construct($_name = '', $_fields = array())
    {
        $this->_name = $_name;
        $this->_fields = $_fields;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param mixed $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @param mixed $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->_errors = $errors;
        return $this;
    }

    public function addField(FieldInterface $field = null, $groupName = '')
    {
        if(is_null($field)){
            return $this;
        }
        if (!empty($groupName)) {
            /** @var FormGroup $group */
            $group = isset($this->_groups[$groupName]) ? $this->_groups[$groupName] : null;
            if (is_object($group)) {
                $group->addField($field);
            }
        } else {
            $this->_fields[$field->getName()] = $field;
        }
        return $this;
    }

    public function getField($fieldName)
    {
        return !empty($this->_fields[$fieldName]) ? $this->_fields[$fieldName] : null;
    }

    public function renderView($opts = array())
    {
        $container = Container::getInstance();
        /** @var FormView $formView */
        $formView = $container->offsetGet('wwp.forms.formView');
        $formView->setFormInstance($this);
        return $formView->render($opts);
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->_groups;
    }

    /**
     * @param mixed $groups
     * @return $this
     */
    public function setGroups($groups)
    {
        $this->_groups = $groups;
        return $this;
    }

    public function addGroup(FormGroup $group)
    {
        $this->_groups[$group->getName()] = $group;
        return $this;
    }

    public function getGroup($groupName){
        return isset($this->_groups[$groupName]) ? $this->_groups[$groupName] : null;
    }

    public function removeGroup($groupName){
        if(isset($this->_groups[$groupName])){ unset($this->_groups[$groupName]); }
        return $this;
    }

    public function fill(array $data)
    {
        $fields = $this->getFields();
        if (!empty($fields)) {
            foreach ($fields as $f) {
                $this->fillField($f, $data);
            }
        }

        $groups = $this->getGroups();
        /** @var $group FormGroup */
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $fields = $group->getFields();
                if (!empty($fields)) {
                    foreach ($fields as $f) {
                        if ($f instanceof FieldGroup) {
                            $groupedFields = $f->getGroup();
                            if (!empty($groupedFields)) {
                                foreach ($groupedFields as $groupedField) {
                                    $this->fillField($groupedField, $data);
                                }
                            }
                        } else {
                            $this->fillField($f, $data);
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function fillField(AbstractField $f, $data)
    {
        $displayRules = $f->getDisplayRules();
        if (is_array($displayRules) && is_array($displayRules['inputAttributes']) && !empty($displayRules['inputAttributes']['name'])) {
            $name = str_replace(']', '', $displayRules['inputAttributes']['name']);
            $dataPath = explode('[', $name);
            if (!empty($dataPath)) {
                foreach ($dataPath as $ndx) {
                    if (empty($data[$ndx])) {
                        $isMultiple = !empty($displayRules['inputAttributes']['multiple']);
                        $data = $isMultiple ? array() : null;
                        continue;
                    } else {
                        $data = $data[$ndx];
                    }
                }
            }
            $f->setValue($data);
        }
        return $this;
    }

}