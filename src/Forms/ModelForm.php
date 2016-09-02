<?php

namespace WonderWp\Forms;

use Doctrine\ORM\Tools\SchemaTool;
use WonderWp\DI\Container;
use WonderWp\Entity\EntityAttribute;
use WonderWp\Forms\Fields\InputField;
use WonderWp\Forms\Fields\TextAreaField;

use Respect\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;

class ModelForm{

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
     * @param mixed $formInstance
     */
    public function setFormInstance($formInstance)
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

    public function buildForm(){

        $attributes = $this->_modelInstance->getAttributes();

        if(!empty($attributes)){ foreach($attributes as $attr){
            /** @var $attr EntityAttribute */
            $f = $this->newField($attr);
            $f->computeDisplayRules($this->specifyDisplayRules($attr));
            $f->computeValidationRules($this->specifyValidationRules($attr));
            //Add field
            $this->_formInstance->addField($f);
        }}

        return $this;
    }

    public function preBuild(){

    }

    public function postBuild(){

    }

    public function newField(EntityAttribute $attr){

        $type=$attr->getType();
        $fieldName = $attr->getFieldName();

        $entity = $this->_modelInstance;

        $val = $entity->$fieldName;
        $label = __($fieldName.'.trad',$this->_textDomain);

        //Field
        switch($type){
            case'text':
                $f = new TextAreaField($fieldName, $val, ['label'=>$label]);
                break;
            default:
                $f = new InputField($fieldName, $val, ['label'=>$label]);
                break;
        }

        return $f;
    }

    public function specifyDisplayRules(EntityAttribute $attr){
        $displayRules = array();

        return $displayRules;
    }

    public function specifyValidationRules(EntityAttribute $attr){

        $validationRules = array();

        //Validate required fields
        if(!$attr->getNullable()){
            $validationRules[] = array(Validator::notEmpty());
        }
        //Validate length
        $length = $attr->getLength();
        if($length > 0){
            $validationRules[] = array(Validator::length(null,$length));
        }
        //validate type
        $type=$attr->getType();
        switch($type){
            case 'integer':
                $validationRules[] = array(Validator::optional(Validator::numeric()));
                break;
            case 'string':
                $validationRules[] = array(Validator::optional(Validator::stringType()));
                break;
        }

        return $validationRules;
    }

    public function handleRequest(Request $request, FormValidatorInterface $formValidator){
        //Form Validation
        $formValidator->setFormInstance($this->_formInstance);
        $data = $this->formatData($request->request->all());
        $errors = $formValidator->validate($data);

        if(empty($errors)){
            $container = Container::getInstance();
            $em = $container->offsetGet('entityManager');
            $this->_modelInstance->populate($data);
            $em->persist($this->_modelInstance);
            $em->flush();
        }

        return $errors;
    }

    public function formatData($data){

        return $data;
    }

}