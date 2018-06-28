<?php

namespace WonderWp\Framework\Form;

use function WonderWp\Framework\array_merge_recursive_distinct;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Form\Field\FieldGroup;
use WonderWp\Framework\Form\Field\FieldInterface;

class Form implements FormInterface
{
    /** @var string */
    protected $name;
    /** @var FieldInterface[] */
    protected $fields = [];
    /** @var array */
    protected $errors = [];
    /** @var array */
    protected $groups = [];

    /**
     * @param string           $name
     * @param FieldInterface[] $fields
     */
    public function __construct($name = '', array $fields = [])
    {
        $this->name   = $name;
        $this->fields = $fields;
    }

    /** @inheritdoc */
    public function renderView(array $opts = [])
    {
        $formView = $this->getView();

        return $formView->render($opts);
    }

    /** @inheritdoc */
    public function getView()
    {
        $container = Container::getInstance();
        /** @var FormView $formView */
        $formView = $container['wwp.forms.formView'];
        $formView->setFormInstance($this);

        return $formView;
    }

    /** @inheritdoc */
    public function getName()
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /** @inheritdoc */
    public function getFields()
    {
        return $this->fields;
    }

    /** @inheritdoc */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /** @inheritdoc */
    public function getErrors()
    {
        return $this->errors;
    }

    /** @inheritdoc */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /** @inheritdoc */
    public function addField(FieldInterface $field = null, $groupName = '')
    {
        if (is_null($field)) {
            return $this;
        }
        if (!empty($groupName)) {
            /** @var FormGroup $group */
            $group = isset($this->groups[$groupName]) ? $this->groups[$groupName] : null;
            if (is_object($group)) {
                $group->addField($field);
            }
        } else {
            $this->fields[$field->getName()] = $field;
        }

        return $this;
    }

    /** @inheritdoc */
    public function getField($name)
    {
        return !empty($this->fields[$name]) ? $this->fields[$name] : null;
    }

    /** @inheritdoc */
    public function removeFields(array $fieldNames)
    {
        if (!empty($fieldNames)) {
            foreach ($fieldNames as $fieldName) {
                if (isset($this->fields[$fieldName])) {
                    unset($this->fields[$fieldName]);
                }
            }
        }

        return $this;
    }

    /** @inheritdoc */
    public function getGroups()
    {
        return $this->groups;
    }

    /** @inheritdoc */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /** @inheritdoc */
    public function getGroup($name)
    {
        return array_key_exists($name, $this->groups) ? $this->groups[$name] : null;
    }

    /** @inheritdoc */
    public function addGroup(FormGroup $group)
    {
        $this->groups[$group->getName()] = $group;

        return $this;
    }

    /** @inheritdoc */
    public function removeGroup($name)
    {
        if (array_key_exists($name, $this->groups)) {
            unset($this->groups[$name]);
        }

        return $this;
    }

    /** @inheritdoc */
    public function fill(array $data)
    {

        $formData = $this->getValues();
        $data     = array_merge_recursive_distinct($formData, $data);

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

    /**
     * @param FieldInterface $f
     * @param mixed          $data
     *
     * @return static
     */
    protected function fillField(FieldInterface $f, $data)
    {
        $displayRules = $f->getDisplayRules();

        if (is_array($displayRules) && is_array($displayRules['inputAttributes']) && !empty($displayRules['inputAttributes']['name'])) {
            $name     = str_replace(']', '', $displayRules['inputAttributes']['name']);
            $dataPath = explode('[', $name);
            if (!empty($dataPath)) {
                foreach ($dataPath as $ndx) {
                    if (empty($data[$ndx])) {
                        $isMultiple = !empty($displayRules['inputAttributes']['multiple']);
                        $data       = $isMultiple ? [] : null;
                        continue;
                    } else {
                        $data = is_string($data[$ndx]) && strpos($data[$ndx], '\\\\') !== false ? str_replace('\\\\', '\\', $data[$ndx]) : $data[$ndx];
                        if (is_string($data)) {
                            $data = stripslashes($data);
                        }
                    }
                }
            }

            if ($f instanceof FieldGroup && is_array($data)) {
                foreach ($f->getGroup() as $subField) {
                    if (isset($data[$subField->getName()])) {
                        $subField->setValue($data[$subField->getName()]);
                    }
                }
            } else {
                $f->setValue($data);
            }
        }

        return $this;
    }

    /** @inheritdoc */
    public function getValues()
    {
        $values = [];

        $fields = $this->getFields();
        if (!empty($fields)) {
            foreach ($fields as $f) {
                $values[$f->getName()] = $f->getValue();
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
                                    $values[$groupedField->getName()] = $groupedField->getValue();
                                }
                            }
                        } else {
                            $values[$f->getName()] = $f->getValue();
                        }
                    }
                }
            }
        }

        return $values;
    }
}
