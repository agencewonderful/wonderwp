<?php

namespace WonderWp\Forms;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpFoundation\Request;
use WonderWp\DI\Container;
use WonderWp\Entity\AbstractEntity;
use WonderWp\Entity\EntityAttribute;
use WonderWp\Entity\EntityRelation;
use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Fields\BooleanField;
use WonderWp\Forms\Fields\DateField;
use WonderWp\Forms\Fields\HiddenField;
use WonderWp\Forms\Fields\InputField;
use WonderWp\Forms\Fields\NumericField;
use WonderWp\Forms\Fields\TextAreaField;
use Respect\Validation\Validator;

class ModelForm
{
    /**
     * @var AbstractEntity
     */
    protected $_modelInstance;

    /** @var Form */
    protected $_formInstance;

    protected $_textDomain;

    /**
     * @return mixed
     */
    public function getModelInstance()
    {
        return $this->_modelInstance;
    }

    /**
     * @param mixed $modelInstance
     */
    public function setModelInstance($modelInstance)
    {
        $this->_modelInstance = $modelInstance;
    }

    /**
     * @return mixed
     */
    public function getFormInstance()
    {
        return $this->_formInstance;
    }

    /**
     * @param FormInterface $formInstance
     * @return $this
     */
    public function setFormInstance(FormInterface $formInstance)
    {
        $this->_formInstance = $formInstance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextDomain()
    {
        return $this->_textDomain;
    }

    /**
     * @param mixed $textDomain
     */
    public function setTextDomain($textDomain)
    {
        $this->_textDomain = $textDomain;
    }

    public function buildForm()
    {
        $this->preBuild();

        //Form Entity Fields
        $attributes = $this->_modelInstance->getFields();
        if (!empty($attributes)) {
            foreach ($attributes as $attr) {
                /** @var $attr EntityAttribute */
                $f = $this->newField($attr);
                if ($f === null) continue;
                $f->computeDisplayRules($this->specifyDisplayRules($attr));
                if (count($f->getValidationRules()) < 1) {
                    //Add default validation rules if not manually specified
                    $f->computeValidationRules($this->specifyValidationRules($attr));
                }
                //Add field
                $this->addField($f);
            }
        }

        //Form Relation Fields
        $relations = $this->_modelInstance->getRelations();
        if (!empty($relations)) {
            foreach ($relations as $attr) {
                /** @var $attr EntityRelation */
                $f = $this->newRelation($attr);
                if (!empty($f)) {
                    //Add field
                    $this->addField($f);
                }
            }
        }

        $this->postBuild();

        return $this;
    }

    public function preBuild()
    {

    }

    public function postBuild()
    {

    }

    public function newField(EntityAttribute $attr)
    {

        $type = $attr->getType();
        $fieldName = $attr->getFieldName();

        $entity = $this->_modelInstance;

        $val = $entity->$fieldName;
        $label = __($fieldName . '.trad', $this->_textDomain);

        //Field
        switch($fieldName){
            case 'createdAt':
            case 'updatedAt':
                $f=null;
                return $f;
                break;
        }
        switch ($type) {
            case 'integer':
                if ($attr->getIsId()) {
                    $f = new HiddenField($fieldName, $val);
                } else {
                    $f = new NumericField($fieldName, $val, ['label' => $label]);
                }
                break;
            case'text':
                $f = new TextAreaField($fieldName, $val, ['label' => $label]);
                break;
            case'date':
            case'datetime':
                $f = new DateField($fieldName, (!empty($val) && $val instanceof \DateTime) ? $val->format('Y-m-d') : null, ['label' => $label]);
                break;
            case'boolean':
            case'bool':
                $f = new BooleanField($fieldName,$val, ['label'=>$label]);
                break;
            default:
                $f = new InputField($fieldName, $val, ['label' => $label]);
                break;
        }

        return $f;
    }

    public function specifyDisplayRules(EntityAttribute $attr)
    {
        $displayRules = array();

        return $displayRules;
    }

    public function specifyValidationRules(EntityAttribute $attr)
    {

        $validationRules = array();

        //Validate required fields
        if (!$attr->getNullable() && !$attr->getIsId()) {
            $validationRules[] = Validator::notEmpty();
        }
        //Validate length
        $length = $attr->getLength();
        if ($length > 0) {
            $validationRules[] = Validator::length(null, $length);
        }
        //validate type
        $type = $attr->getType();
        switch ($type) {
            case 'integer':
                $validationRules[] = Validator::optional(Validator::numeric());
                break;
            case 'string':
                $validationRules[] = Validator::optional(Validator::stringType());
                break;
        }

        return $validationRules;
    }

    public function addField(AbstractField $f, $groupName = '')
    {
        //Add field
        $this->_formInstance->addField($f, $groupName);
    }

    public function newRelation(EntityRelation $relationAttr)
    {

    }

    public function handleRequest(array $data, FormValidatorInterface $formValidator)
    {
        //Fill form with posted data
        $this->_formInstance->fill($data);

        //Form Validation
        $formValidator->setFormInstance($this->_formInstance);
        $errors = $formValidator->validate($data, $this->_textDomain);

        if (empty($errors)) {
            $container = Container::getInstance();
            $em = $container->offsetGet('entityManager');
            $this->_modelInstance->populate($data);

            $em->persist($this->_modelInstance);
            $em->flush();
        }

        return $errors;
    }

    public function formatData($data)
    {

        return $data;
    }

}
