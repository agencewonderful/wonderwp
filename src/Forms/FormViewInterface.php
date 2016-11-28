<?php

namespace WonderWp\Forms;

use WonderWp\Forms\Fields\FieldInterface;

interface FormViewInterface {

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setFormInstance(FormInterface $form);

    /**
     * @return FormInterface
     */
    public function getFormInstance();

    /**
     * @param array $opts
     * @return mixed
     */
    public function render($opts=array());

    /**
     * @param array $optsStart
     * @return mixed
     */
    public function formStart($optsStart=array());

    /**
     * @param $fieldName
     * @return mixed
     */
    public function renderField($fieldName);

    /**
     * @param $fieldName
     * @return mixed
     */
    public function fieldWrapStart($fieldName);

    /**
     * @param $fieldName
     * @return mixed
     */
    public function fieldLabel($fieldName);

    /**
     * @param $fieldName
     * @return mixed
     */
    public function fieldStart($fieldName);

    /**
     * @param $fieldName
     * @return mixed
     */
    public function fieldBetween($fieldName);

    /**
     * @param $fieldName
     * @return mixed
     */
    public function fieldEnd($fieldName);

    /**
     * @param $fieldName
     * @return mixed
     */
    public function fieldWrapEnd($fieldName);

    /**
     * @param array $optsEnd
     * @return mixed
     */
    public function formEnd($optsEnd=array());

}