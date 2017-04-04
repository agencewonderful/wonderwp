<?php

namespace WonderWp\Framework\Form;

use WonderWp\Framework\Form\Fields\FieldInterface;

class FormGroup
{
    /**
     * @var string
     */
    protected $_name;
    /**
     * @var string
     */
    protected $_title;
    /**
     * @var FieldInterface[]
     */
    protected $_fields;
    /**
     * @var array
     */
    protected $displayRules = [];

    /**
     * FormGroup constructor.
     *
     * @param       $_name
     * @param       $_title
     * @param array $displayRules
     */
    public function __construct($_name, $_title, $displayRules = [])
    {
        $this->_name        = $_name;
        $this->_title       = $_title;
        $this->displayRules = $displayRules;

        if (empty($this->displayRules['class'])) {
            $this->displayRules['class'] = [];
        }
        if (is_admin()) {
            $this->displayRules['class'][] = 'postbox';
            $this->displayRules['class'][] = 'form-group-' . $this->_name;
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
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
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    /**
     * @param FieldInterface $field
     */
    public function addField(FieldInterface $field)
    {
        $this->_fields[$field->getName()] = $field;
    }

    /**
     * @return array
     */
    public function getDisplayRules()
    {
        return $this->displayRules;
    }

    /**
     * @param array $displayRules
     */
    public function setDisplayRules($displayRules)
    {
        $this->displayRules = $displayRules;
    }
}
