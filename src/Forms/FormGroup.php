<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 02/09/2016
 * Time: 16:52
 */

namespace WonderWp\Forms;

use WonderWp\Forms\Fields\AbstractField;

class FormGroup{

    private $_name;
    private $_title;
    private $_fields;
    protected $displayRules = array();

    /**
     * FormGroup constructor.
     * @param $_name
     * @param $_title
     */
    public function __construct($_name, $_title, $displayRules=array())
    {
        $this->_name = $_name;
        $this->_title = $_title;
        $this->displayRules = $displayRules;

        if(empty($this->displayRules['class'])){ $this->displayRules['class'] = array(); }
        if(is_admin()) {
            $this->displayRules['class'][] = 'postbox';
            $this->displayRules['class'][] = 'form-group-'.$this->_name;
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

    public function addField(AbstractField $field){
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