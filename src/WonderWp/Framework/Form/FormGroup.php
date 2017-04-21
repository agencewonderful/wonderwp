<?php

namespace WonderWp\Framework\Form;

use WonderWp\Framework\Form\Field\FieldInterface;

class FormGroup
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $title;
    /** @var FieldInterface[] */
    protected $fields;
    /** @var array */
    protected $displayRules = [];

    /**
     * FormGroup constructor.
     *
     * @param string $name
     * @param string $title
     * @param array  $displayRules
     */
    public function __construct($name, $title, $displayRules = [])
    {
        $this->name         = $name;
        $this->title        = $title;
        $this->displayRules = $displayRules;

        if (empty($this->displayRules['class'])) {
            $this->displayRules['class'] = [];
        }
        if (is_admin()) {
            $this->displayRules['class'][] = 'postbox';
            $this->displayRules['class'][] = 'form-group-' . $this->name;
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param FieldInterface $field
     */
    public function addField(FieldInterface $field)
    {
        $this->fields[$field->getName()] = $field;
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
